<script setup>
import {
  ref,
  onMounted,
  onUnmounted,
  watch,
  nextTick,
  provide,
  computed,
  reactive,
} from "vue";
import BaseBreadcrumb from "@/components/UI/BaseBreadcrumb.vue";
import ProductGallery from "@/components/product/ProductGallery.vue";
import ProductInfo from "@/components/product/ProductInfo.vue";
import ProductDetails from "@/components/product/ProductDetails.vue";
import RelatedProducts from "@/components/product/RelatedProducts.vue";
import AlsoBoughtProducts from "@/components/product/AlsoBoughtProducts.vue";
import ProductComments from "@/components/product/ProductComments.vue";
import StickyPurchaseBar from "@/components/product/StickyPurchaseBar.vue";
import { useWishlist } from "@/composables/useWishlist";

const props = defineProps({
  slug: String, // از Blade گرفته میشه: data-slug
});

const product = ref(null);
const similarProducts = ref([]);
const alsoBoughtProducts = ref([]);
const breadcrumbs = ref([]);
const finalPrice = ref(null);
const isLoading = ref(null);

onMounted(async () => {
  try {
    isLoading.value = true;

    const res = await fetch(`/api/products/${props.slug}`);
    const data = await res.json();

    product.value = data.product;
    similarProducts.value = Array.isArray(data.similarProducts)
      ? data.similarProducts
      : [];
    alsoBoughtProducts.value = Array.isArray(data.alsoBoughtProducts)
      ? data.alsoBoughtProducts
      : [];
    breadcrumbs.value = data.breadcrumbs;
    finalPrice.value = data.final_price;

    window.currentProductId = product.value.id;

    // 📦 اضافه کردن داده JSON-LD برای سئو
    addBreadcrumbJsonLd(breadcrumbs.value);
    addProductJsonLd(product.value);
  } catch (error) {
    console.error("خطا در دریافت محصول:", error);
  } finally {
    isLoading.value = false;
  }
});

function addBreadcrumbJsonLd(items) {
  const jsonLd = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((item, index) => ({
      "@type": "ListItem",
      position: index + 1,
      name: item.label,
      item: item.url,
    })),
  };

  const script = document.createElement("script");
  script.type = "application/ld+json";
  script.text = JSON.stringify(jsonLd);
  document.head.appendChild(script);
}

function addProductJsonLd(product) {
  let availabilityStatus = product.inventory > 0 ? "InStock" : "OutOfStock";

  function stripHtml(html) {
    let tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
  }

  const jsonLd = {
    "@context": "https://schema.org",
    "@type": "Product",
    name: product.name,
    image: [product.image],
    description: stripHtml(product.description),
    sku: product.id,
    brand: {
      "@type": "Brand",
      name: "نووارو",
    },
    offers: {
      "@type": "Offer",
      url: window.location.pathname,
      priceCurrency: "IRR",
      price: (product.sale_price ?? product.price) * 10,
      priceValidUntil: product.sale_price
        ? new Date(product.sale_end_at).toDateString()
        : null,
      availability: `https://schema.org/${availabilityStatus}`,
    },
  };

  const script = document.createElement("script");
  script.type = "application/ld+json";
  script.text = JSON.stringify(jsonLd);
  document.head.appendChild(script);
}

const productInfoRef = ref(null);
const stickyVisible = ref(false);
const actionsWrapEl = ref(null);

let observer = null;

function setupObserver() {
  observer?.disconnect();
  const target = actionsWrapEl?.value;
  if (!target || !("IntersectionObserver" in window)) return;
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        stickyVisible.value = entry.isIntersecting;
      });
    },
    { threshold: 0 }
  );
  observer.observe(target);
}

onMounted(() => nextTick(setupObserver));
onUnmounted(() => observer?.disconnect());
// Navigating to a different product re-targets the observer at the new
// actions panel once the DOM has updated.
watch(
  () => product.value?.id,
  () => nextTick(setupObserver)
);

const { has, toggle } = useWishlist();

const images = computed(() => {
  return [{ image: product.value.image }, ...product.value.gallery];
});

const stockInfo = computed(() => {
  if (!product.value) return null;
  if (product.value.inventory <= 0) return { tone: "out", label: "ناموجود" };
  if (product.value.inventory <= 8)
    return {
      tone: "low",
      label: `موجودی محدود — ${toFa(product.value.inventory)} عدد باقی‌مانده`,
    };
  return { tone: "in", label: "موجود" };
});
</script>

<template>
  <template v-if="product && !isLoading">
    <main id="main">
      <div class="container pdp-top">
        <BaseBreadcrumb :items="breadcrumbs" />
      </div>

      <button
        type="button"
        class="icon-btn product-card__wishlist"
        :aria-pressed="has(product.id) ? 'true' : 'false'"
        :aria-label="`افزودن «${product.title}» به علاقه‌مندی‌ها`"
        @click.stop="toggle(product.id)"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true" class="heart-fill">
          <path
            d="M12 20s-7-4.4-9.3-8.8C1.3 8 2.6 5 5.8 4.4c2-.4 3.7.5 4.9 2.1 1.2-1.6 2.9-2.5 4.9-2.1 3.2.6 4.5 3.6 3.1 6.8C19 15.6 12 20 12 20Z"
          />
        </svg>
      </button>

      <div class="pdp container">
        <ProductGallery :product="product" />

        <ProductInfo
          ref="productInfoRef"
          :product="product"
          :stock-info="stockInfo"
          :final-price="finalPrice"
        />
      </div>

      <ProductDetails
        :product="product"
        :description="product.description"
        :care="product.care"
      />

      <div ref="actionsWrapEl">
        <RelatedProducts v-if="similarProducts" :products="similarProducts" />
        <AlsoBoughtProducts
          v-if="alsoBoughtProducts"
          :products="alsoBoughtProducts"
        />

        <ProductComments
          :comments="product.approved_comments"
          :product="product"
        />
      </div>
    </main>

    <StickyPurchaseBar :product="product" :is-visible="stickyVisible" />
  </template>

  <main
    v-else-if="!product && !isLoading"
    id="main"
    class="container"
    style="padding: var(--sp-16) 0; min-height: 50vh"
  >
    <p>محصولی با این شناسه پیدا نشد.</p>
    <a href="/products" class="btn btn--outline">بازگشت به فروشگاه</a>
  </main>
  <!-- 🌀 Loader -->
  <div v-if="isLoading" style="padding: var(--sp-16) 0; min-height: 50vh">
    <base-spinner></base-spinner>
  </div>
</template>

<style scoped>
.pdp-gallery__stage {
  position: relative;
  aspect-ratio: 4/5;
  background: #edeff3;
  border-radius: var(--r-md);
  overflow: hidden;
}
.pdp-gallery__stage img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.pdp-gallery__countdown {
  position: absolute;
  top: var(--sp-3);
  inset-inline-start: var(--sp-3);
}
.pdp-gallery__thumbs {
  display: flex;
  gap: var(--sp-2);
  margin-top: var(--sp-3);
}
.pdp-gallery__thumb {
  width: 64px;
  height: 80px;
  border-radius: var(--r-sm);
  overflow: hidden;
  border: 1px solid var(--c-border);
  padding: 0;
  cursor: pointer;
}
.pdp-gallery__thumb.is-active {
  border-color: var(--c-navy);
}
.pdp-gallery__thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>