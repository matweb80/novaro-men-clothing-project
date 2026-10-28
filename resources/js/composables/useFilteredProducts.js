import { computed, nextTick, reactive, ref } from "vue";

const DEFAULT_SORT = "newest";

const VALID_SORTS = new Set([
    "newest",
    "bestselling",
    "cheapest",
    "expensive",
    "discount",
]);

export function useFilteredProducts() {
    const attributes = ref([]);
    const products = ref([]);
    const categoryProducts = ref([]);
    const tagProducts = ref([]);
    const totalResults = ref(0);

    const priceRange = reactive({
        min: 0,
        max: 0,
    });

    const category = ref({});
    const categorySlug = ref(null);

    const tag = ref({});
    const tagSlug = ref(null);

    const currentPage = ref(1);
    const lastPage = ref(1);

    const isLoading = ref(false);

    const sort = ref(DEFAULT_SORT);

    const filters = reactive({
        search: "",
        min_price: null,
        max_price: null,
        special_price: false,
        in_stock_only: false,
        attributes: {},
    });

    /*
    |--------------------------------------------------------------------------
    | Active Filters Count
    |--------------------------------------------------------------------------
    */

    const activeFilterCount = computed(() => {
        const attributeCount = Object.values(filters.attributes).reduce(
            (total, values) => {
                return total + (Array.isArray(values) ? values.length : 0);
            },
            0,
        );

        return (
            (filters.search !== "" ? 1 : 0) +
            (filters.min_price != null ? 1 : 0) +
            (filters.max_price != null ? 1 : 0) +
            (filters.special_price ? 1 : 0) +
            (filters.in_stock_only ? 1 : 0) +
            attributeCount
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    function toggleAttribute(attributeId, valueId) {
        if (!filters.attributes[attributeId]) {
            filters.attributes[attributeId] = [];
        }

        const values = filters.attributes[attributeId];
        const index = values.indexOf(valueId);

        if (index === -1) {
            values.push(valueId);
        } else {
            values.splice(index, 1);
        }

        currentPage.value = 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

    async function setSort(id) {
        if (!VALID_SORTS.has(id)) {
            return;
        }

        if (sort.value === id) {
            return;
        }

        sort.value = id;

        if (products.value?.length > 0) await applyFilters(1);
        else if (categoryProducts.value.data?.length > 0)
            await fetchCategoryProducts(1, true, categorySlug.value);
        else if (tagProducts.value.data?.length > 0)
            await fetchTagProducts(1, true, tagSlug.value);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes / Filters API
    |--------------------------------------------------------------------------
    */

    async function getFilters() {
        try {
            const response = await fetch("/api/filters");

            if (!response.ok) {
                throw new Error(`Failed to fetch filters: ${response.status}`);
            }

            const data = await response.json();

            attributes.value = Array.isArray(data) ? data : [];

            attributes.value.forEach((attribute) => {
                if (!filters.attributes[attribute.id]) {
                    filters.attributes[attribute.id] = [];
                }
            });
        } catch (error) {
            console.error("خطا در دریافت ویژگی‌ها:", error);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Query String
    |--------------------------------------------------------------------------
    */

    function buildQueryString(page = currentPage.value) {
        const params = new URLSearchParams();

        if (filters.search.trim()) {
            params.set("search", filters.search.trim());
        }

        if (filters.min_price != null && filters.min_price !== "") {
            params.set("min_price", filters.min_price);
        }

        if (filters.max_price != null && filters.max_price !== "") {
            params.set("max_price", filters.max_price);
        }

        if (filters.special_price) {
            params.set("special_price", "1");
        }

        if (filters.in_stock_only) {
            params.set("in_stock_only", "1");
        }

        /*
         * attributes[attribute_id][] = value_id
         */
        Object.entries(filters.attributes).forEach(
            ([attributeId, valueIds]) => {
                if (!Array.isArray(valueIds)) {
                    return;
                }

                valueIds.forEach((valueId) => {
                    params.append(`attributes[${attributeId}][]`, valueId);
                });
            },
        );

        /*
         * newest is backend default,
         * therefore we don't need ?sort=newest.
         */
        if (sort.value !== DEFAULT_SORT) {
            params.set("sort", sort.value);
        }

        if (page > 1) {
            params.set("page", page);
        }

        return params.toString();
    }

    /*
    |--------------------------------------------------------------------------
    | URL State
    |--------------------------------------------------------------------------
    */

    function loadFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);

        filters.search = params.get("search") || "";

        filters.min_price = params.get("min_price")
            ? Number(params.get("min_price"))
            : null;

        filters.max_price = params.get("max_price")
            ? Number(params.get("max_price"))
            : null;

        filters.special_price = params.get("special_price") === "1";

        filters.in_stock_only = params.get("in_stock_only") === "1";

        const urlSort = params.get("sort");

        sort.value = VALID_SORTS.has(urlSort) ? urlSort : DEFAULT_SORT;

        const attributesFromUrl = {};

        for (const [key, value] of params.entries()) {
            const match = key.match(/^attributes\[(\d+)\]\[\]$/);

            if (!match) {
                continue;
            }

            const attributeId = match[1];

            if (!attributesFromUrl[attributeId]) {
                attributesFromUrl[attributeId] = [];
            }

            attributesFromUrl[attributeId].push(Number(value));
        }

        filters.attributes = attributesFromUrl;

        const page = Number.parseInt(params.get("page"), 10);

        currentPage.value = Number.isInteger(page) && page > 0 ? page : 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Products API
    |--------------------------------------------------------------------------
    */

    async function applyFilters(page = 1, pushToUrl = true) {
        if (isLoading.value) {
            return;
        }

        isLoading.value = true;
        currentPage.value = page;

        try {
            const queryString = buildQueryString(page);

            const url = queryString
                ? `/api/products?${queryString}`
                : "/api/products";

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`Failed to fetch products: ${response.status}`);
            }

            const data = await response.json();

            products.value = Array.isArray(data.data) ? data.data : [];

            if (data.price_range) {
                priceRange.min = Number(data.price_range.min ?? 0);
                priceRange.max = Number(data.price_range.max ?? 0);
            }

            totalResults.value = Number(data.total ?? 0);

            lastPage.value = Number(data.last_page ?? 1);

            /*
             * If requested page no longer exists,
             * return to the last valid page.
             */
            if (currentPage.value > lastPage.value && lastPage.value > 0) {
                currentPage.value = lastPage.value;

                await applyFilters(lastPage.value, pushToUrl);

                return;
            }

            if (pushToUrl) {
                const newUrl = queryString
                    ? `${window.location.pathname}?${queryString}`
                    : window.location.pathname;

                window.history.pushState({}, "", newUrl);
            }
        } catch (error) {
            console.error("خطا در دریافت محصولات:", error);

            products.value = [];
            totalResults.value = 0;
            lastPage.value = 1;
        } finally {
            isLoading.value = false;
        }
    }

    const fetchCategoryProducts = async (page = 1, pushToUrl = true, slug) => {
        try {
            isLoading.value = true;
            currentPage.value = page;
            categorySlug.value = slug;

            const queryString = buildQueryString(page);
            const res = await fetch(`/api/categories/${slug}?${queryString}`);
            const data = await res.json();
            category.value = data.category;
            categoryProducts.value = data.products;

            totalResults.value = data.products.total ?? 0;
            lastPage.value = data.products.last_page ?? 1;

            /*
             * If requested page no longer exists,
             * return to the last valid page.
             */
            if (currentPage.value > lastPage.value && lastPage.value > 0) {
                currentPage.value = lastPage.value;

                await fetchCategoryProducts(lastPage.value, pushToUrl);

                return;
            }
            // ذخیره فیلترها در URL
            if (pushToUrl) {
                const newUrl = `${window.location.pathname}?${queryString}`;
                window.history.pushState({}, "", newUrl);
            }
        } catch (error) {
            console.error(error);
        } finally {
            isLoading.value = false;
        }
    };

    const fetchTagProducts = async (page = 1, pushToUrl = true, slug) => {
        try {
            isLoading.value = true;
            currentPage.value = page;
            tagSlug.value = slug;

            const queryString = buildQueryString(page);
            const res = await fetch(`/api/tags/${slug}?${queryString}`);
            const data = await res.json();
            tag.value = data.tag;
            tagProducts.value = data.products;

            totalResults.value = data.products.total ?? 0;
            lastPage.value = data.products.last_page ?? 1;

            /*
             * If requested page no longer exists,
             * return to the last valid page.
             */
            if (currentPage.value > lastPage.value && lastPage.value > 0) {
                currentPage.value = lastPage.value;

                await fetchTagProducts(lastPage.value, pushToUrl);

                return;
            }
            // ذخیره فیلترها در URL
            if (pushToUrl) {
                const newUrl = `${window.location.pathname}?${queryString}`;
                window.history.pushState({}, "", newUrl);
            }
        } catch (error) {
            console.error(error);
        } finally {
            isLoading.value = false;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    async function changePage(page) {
        const targetPage = Number(page);

        if (
            !Number.isInteger(targetPage) ||
            targetPage < 1 ||
            targetPage > lastPage.value ||
            targetPage === currentPage.value
        ) {
            return;
        }

        await applyFilters(targetPage);

        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    }

    const visiblePages = computed(() => {
        const total = lastPage.value;
        const current = currentPage.value;

        if (total <= 7) {
            return Array.from({ length: total }, (_, index) => index + 1);
        }

        const pages = [1];

        if (current > 4) {
            pages.push("...");
        }

        const start = Math.max(2, current - 2);
        const end = Math.min(total - 1, current + 2);

        for (let page = start; page <= end; page++) {
            pages.push(page);
        }

        if (current < total - 3) {
            pages.push("...");
        }

        pages.push(total);

        return pages;
    });

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    async function resetFilters() {
        filters.search = "";
        filters.min_price = null;
        filters.max_price = null;
        filters.special_price = false;
        filters.in_stock_only = false;

        Object.keys(filters.attributes).forEach((attributeId) => {
            filters.attributes[attributeId] = [];
        });

        sort.value = DEFAULT_SORT;

        currentPage.value = 1;

        window.history.pushState({}, "", window.location.pathname);

        await nextTick();

        await applyFilters(1, false);
    }

    return {
        getFilters,
        loadFiltersFromUrl,
        applyFilters,
        resetFilters,

        fetchCategoryProducts,
        category,

        fetchTagProducts,
        tag,

        attributes,
        products,
        categoryProducts,
        tagProducts,
        totalResults,

        priceRange,

        currentPage,
        lastPage,
        isLoading,

        filters,

        changePage,
        visiblePages,

        activeFilterCount,

        sort,
        setSort,

        toggleAttribute,
    };
}
