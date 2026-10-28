<script setup>
import {
  CATEGORY_TREE,
  CATEGORY_IMAGES,
  FEATURED_CATEGORY_IDS,
} from "@/data/categories";
import { findCategoryById } from "@/utils/category";
import ImagePlate from "@/components/ui/ImagePlate.vue";

const featuredCategories = FEATURED_CATEGORY_IDS.map((id) => ({
  id,
  category: findCategoryById(CATEGORY_TREE, id),
  image: CATEGORY_IMAGES[id],
})).filter((entry) => entry.category);
</script>

<template>
  <section class="cat-showcase" aria-labelledby="catShowcaseTitle">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">کاوش کنید</p>
        <h2 id="catShowcaseTitle">دسته‌بندی‌ها</h2>
      </div>
      <div class="cat-grid">
        <a
          v-for="entry in featuredCategories"
          :key="entry.id"
          href="#"
          class="cat-tile"
        >
          <ImagePlate
            class="cat-tile__media"
            :src="entry.image?.src ?? ''"
            :alt="entry.image?.alt ?? ''"
            :placeholder-text="`NOVARO — ${entry.id.toUpperCase()}`"
          />
          <span class="cat-tile__label">
            <span>{{ entry.category.name }}</span>
            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M15 5l-7 7 7 7" />
            </svg>
          </span>
        </a>
      </div>
    </div>
  </section>
</template>

<style scoped>
.cat-showcase {
  margin-bottom: 24px;
}

@media (max-width: 576px) {
  .cat-showcase {
  margin-bottom: 0px;
}
  .cat-grid {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: 1.3fr 1fr;
    gap: var(--sp-5);
  }
  .cat-tile {
    display: flex;
    flex-direction: column;
  }
  .cat-tile__media {
    flex: 1;
    margin-bottom: var(--sp-3);
  }
  .cat-tile:nth-child(1) {
    grid-column: 1 / 6;
    grid-row: 1;
  }
  .cat-tile:nth-child(2) {
    grid-column: 1;
    grid-row: 2;
  }
  .cat-tile:nth-child(3) {
    grid-column: 2;
    grid-row: 2;
  }
  .cat-tile:nth-child(1) .cat-tile__media {
    aspect-ratio: auto;
  }
  .cat-tile:nth-child(2) .cat-tile__media {
    aspect-ratio: 2/3;
  }
  .cat-tile:nth-child(3) .cat-tile__media {
    aspect-ratio: 2/3;
  }
}
</style>