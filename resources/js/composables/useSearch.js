import { ref } from "vue";
import { PRODUCTS } from "@/data/products";
import { CATEGORY_TREE } from "@/data/categories";
import { flattenCategories } from "@/utils/category";

const DEBOUNCE_MS = 380;

/**
 * Search state machine: idle (recent/suggested) → typing → loading → results
 * or empty. Each SearchOverlay instance gets its own state via a fresh call,
 * matching the single overlay in the prototype.
 */
export function useSearch() {
    const query = ref("");
    const status = ref("idle"); // idle | loading | results
    const matchedCategories = ref([]);
    const matchedProducts = ref([]);
    const matchedTags = ref([]);

    const liveMessage = ref("");

    let timeout = null;

    function runSearch() {
        clearTimeout(timeout);

        timeout = setTimeout(async () => {
            if (!query.value.trim()) {
                matchedProducts.value = [];
                matchedCategories.value = [];
                matchedTags.value = [];
                status.value = "idle";

                return;
            }
            status.value = "loading";
            try {
                await fetch(
                    `/api/vue/search/all?search=${encodeURIComponent(
                        query.value,
                    )}`,
                )
                    .then((response) => response.json())
                    .then((data) => {
                        matchedProducts.value = data.products;
                        matchedCategories.value = data.categories;
                        matchedTags.value = data.tags;
                    });
                liveMessage.value =
                    matchedCategories.value.length &&
                    matchedProducts.value.length &&
                    matchedTags.value.length
                        ? `${matchedCategories.value.length + matchedProducts.value.length + matchedTags.value.length} نتیجه برای ${query.value} پیدا شد`
                        : `نتیجه‌ای برای ${query.value} پیدا نشد`;
            } catch (error) {
                console.error("Search error:", error);
            } finally {
                status.value = "results";
            }
        }, DEBOUNCE_MS);
    }

    function reset() {
        query.value = "";
        status.value = "idle";
        matchedCategories.value = [];
        matchedProducts.value = [];
        matchedTags.value = [];
        clearTimeout(timeout);
    }

    return {
        query,
        status,
        matchedCategories,
        matchedProducts,
        matchedTags,
        liveMessage,
        runSearch,
        reset,
    };
}
