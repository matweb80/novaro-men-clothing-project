<script setup>
import Breadcrumb from "@/components/layout/Breadcrumb.vue";

defineProps({
  eyebrow: { type: String, default: "سند حقوقی" },
  title: { type: String, required: true },
  lead: { type: String, default: "" },
  sections: { type: Array, required: true }, // [{ id, title, text }]
});
</script>

<template>
  <main id="main">
    <div class="page-hero container">
      <Breadcrumb :current-label="title" />
      <p class="eyebrow">{{ eyebrow }}</p>
      <h1 class="page-hero__title">{{ title }}</h1>
      <p v-if="lead" class="page-hero__lead">{{ lead }}</p>
    </div>

    <section class="legal-doc container">
      <nav class="legal-toc" aria-label="فهرست مطالب">
        <a
          v-for="s in sections"
          :key="s.id"
          :href="`#${s.id}`"
          class="legal-toc__link"
          >{{ s.title }}</a
        >
      </nav>

      <div class="legal-body">
        <section
          v-for="s in sections"
          :key="s.id"
          :id="s.id"
          class="legal-section"
        >
          <h2 class="legal-section__title">{{ s.title }}</h2>
          <p class="legal-section__text legal-section__text--placeholder">
            {{ s.text }}
          </p>
        </section>
      </div>
    </section>
  </main>
</template>

<style scoped>
.page-hero {
  padding-top: var(--sp-10);
  padding-bottom: var(--sp-8);
  max-width: 640px;
}
.page-hero__title {
  font-size: var(--fs-h1);
  font-weight: 500;
  margin-block: var(--sp-2) var(--sp-3);
}
.page-hero__lead {
  color: var(--c-text-muted);
  font-size: var(--fs-body-lg);
}

.legal-doc {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--sp-8);
  padding-bottom: var(--sp-20);
}
@media (min-width: 992px) {
  .legal-doc {
    grid-template-columns: 220px 1fr;
    align-items: start;
  }
}

.legal-toc {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: var(--sp-2) var(--sp-4);
  border-bottom: 1px solid var(--c-border);
  padding-bottom: var(--sp-4);
}
@media (min-width: 992px) {
  .legal-toc {
    flex-direction: column;
    border-bottom: none;
    border-inline-start: 1px solid var(--c-border);
    padding-bottom: 0;
    padding-inline-start: var(--sp-4);
    position: sticky;
    top: var(--sp-8);
  }
}
.legal-toc__link {
  font-size: var(--fs-small);
  color: var(--c-text-muted);
  text-decoration: none;
}
.legal-toc__link:hover {
  color: var(--c-navy);
}

.legal-body {
  max-width: 720px;
}
.legal-section {
  padding-block: var(--sp-6);
  border-bottom: 1px solid var(--c-border);
  scroll-margin-top: var(--sp-8);
}
.legal-section:last-child {
  border-bottom: none;
}
.legal-section__title {
  font-size: var(--fs-h4);
  font-weight: 600;
  margin-bottom: var(--sp-3);
}
.legal-section__text {
  font-size: var(--fs-body);
  color: var(--c-text-muted);
  line-height: var(--lh-normal);
}
.legal-section__text--placeholder {
  font-style: italic;
}
</style>
