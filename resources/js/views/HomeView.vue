<script setup>
import { PRODUCTS, NEW_ARRIVAL_IDS, BEST_SELLER_IDS } from "@/data/products";
import ProductGrid from "@/components/product/ProductGrid.vue";
import ProductSlider from "@/components/product/ProductSlider.vue";
import HeroSection from "@/components/home/HeroSection.vue";
import CategoryShowcase from "@/components/home/CategoryShowcase.vue";
import EditorialSection from "@/components/home/EditorialSection.vue";
import FeaturedCollection from "@/components/home/FeaturedCollection.vue";
import BrandStatement from "@/components/home/BrandStatement.vue";
import NewsletterSection from "@/components/home/NewsletterSection.vue";
import { useSliderProducts } from "@/composables/useSliderProducts";
import { onMounted } from "vue";

const newArrivals = NEW_ARRIVAL_IDS.map((id) =>
  PRODUCTS.find((p) => p.id === id)
).filter(Boolean);
const bestSellers = BEST_SELLER_IDS.map((id) =>
  PRODUCTS.find((p) => p.id === id)
).filter(Boolean);

const { products, getProducts, isLoading } = useSliderProducts();

onMounted(async () => {
  await getProducts();
});
</script>

<template>
  <main id="main">
    <HeroSection />

    <CategoryShowcase />

    <section
      class="pg-section"
      id="new-arrivals"
      aria-labelledby="newArrivalsTitle"
    >
      <div class="container">
        <div class="section-head section-head--split">
          <div>
            <p class="eyebrow">تازه از راه رسیده</p>
            <h2 id="newArrivalsTitle">جدیدترین‌ها</h2>
            <p class="section-head__desc">منتخبی از تازه‌ترین قطعات نوواررو.</p>
          </div>
          <a href="#" class="section-head__link"
            >مشاهده همه <span aria-hidden="true">←</span></a
          >
        </div>
        <ProductSlider :products="products" />
      </div>
    </section>

    <EditorialSection />

    <FeaturedCollection />

    <section
      class="pg-section pg-section--tight"
      aria-labelledby="bestSellersTitle"
    >
      <div class="container">
        <div class="section-head">
          <p class="eyebrow">پرفروش‌ترین‌ها</p>
          <h2 id="bestSellersTitle">منتخب مشتریان</h2>
        </div>
        <ProductSlider :products="products" />
      </div>
    </section>

    <BrandStatement />

    <NewsletterSection />
  </main>
</template>
