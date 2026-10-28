<script setup>
import { computed } from "vue";

const props = defineProps({
  product: { type: Object, required: true },
  description: { type: String, default: null },
  care: { type: String, default: null },
});

const groupedAttributes = computed(() => {
  if (!props.product?.attribute_values) return [];

  const grouped = {};
  for (const item of props.product.attribute_values) {
    const name = item.attribute.name;
    if (!grouped[name]) grouped[name] = [];
    if (!grouped[name].includes(item.value)) {
      grouped[name].push(item.value);
    }
  }
  return grouped;
});

const showAttributes = computed(() => {
  for (const attr in groupedAttributes.value) {
    return attr !== null ? true : false;
  }
});
</script>

<template>
  <section class="pdp-details container" aria-label="جزئیات محصول">
    <div class="container">
      <details v-if="description" class="pdp-details-group" open>
        <summary>
          <span>توضیحات</span
          ><svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 9l6 6 6-6" />
          </svg>
        </summary>
        <div class="pdp-details-group__body">
          <p v-html="description"></p>
        </div>
      </details>

      <details v-if="showAttributes" class="pdp-details-group">
        <summary>
          <span>مشخصات</span
          ><svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 9l6 6 6-6" />
          </svg>
        </summary>
        <div class="pdp-details-group__body">
          <div class="pdp-specs">
            <div
              v-for="(values, attrName) in groupedAttributes"
              :key="attrName"
              class="pdp-specs__row"
            >
              <span>{{ attrName }}</span>
              <span>{{ values.join(" , ") }}</span>
            </div>
          </div>
        </div>
      </details>

      <details v-if="care" class="pdp-details-group">
        <summary>
          <span>نگهداری</span
          ><svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 9l6 6 6-6" />
          </svg>
        </summary>
        <div class="pdp-details-group__body">
          <p>{{ care }}</p>
        </div>
      </details>
    </div>
  </section>
</template>
