<template>
  <div class="attribute-filter">
    <!-- Attribute Header -->
    <button
      class="attribute-filter__header"
      type="button"
      :aria-expanded="isOpen"
      :aria-controls="`attribute-${attr.id}`"
      @click="isOpen = !isOpen"
    >
      <span class="attribute-filter__heading">
        <span class="attribute-filter__name">
          {{ attr.name }}
        </span>

        <span v-if="selectedCount" class="attribute-filter__count">
          {{ selectedCount }}
        </span>
      </span>

      <font-awesome-icon
        icon="fa-solid fa-angle-down"
        class="attribute-filter__icon"
        :class="{ 'is-open': isOpen }"
      />
    </button>

    <!-- Attribute Values -->
    <Transition name="attribute-collapse">
      <div
        v-if="isOpen"
        :id="`attribute-${attr.id}`"
        class="attribute-filter__content"
      >
        <div v-if="attr.values?.length" class="attribute-filter__values">
          <label
            v-for="val in attr.values"
            :key="val.id"
            class="attribute-option"
            :class="{
              'is-selected': isSelected(val.id),
            }"
          >
            <input
              v-model="filters.attributes[attr.id]"
              type="checkbox"
              :value="val.id"
            />

            <span class="attribute-option__checkbox">
              <font-awesome-icon icon="fa-solid fa-check" />
            </span>

            <span class="attribute-option__label">
              {{ val.value }}
            </span>
          </label>
        </div>

        <div v-else class="attribute-filter__empty">
          گزینه‌ای برای این ویژگی وجود ندارد.
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import { computed, ref } from "vue";

export default {
  props: {
    attr: {
      type: Object,
      required: true,
    },

    filters: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    /*
     * Keep the first level of attributes open by default.
     * This gives the sidebar a more useful initial state while
     * keeping the existing filter structure untouched.
     */
    const isOpen = ref(false);

    const selectedValues = computed(() => {
      return props.filters?.attributes?.[props.attr.id] ?? [];
    });

    const selectedCount = computed(() => {
      return Array.isArray(selectedValues.value)
        ? selectedValues.value.length
        : 0;
    });

    const isSelected = (valueId) => {
      return selectedValues.value.includes(valueId);
    };

    return {
      isOpen,
      selectedCount,
      isSelected,
    };
  },
};
</script>

<style scoped>
.attribute-filter {
  width: 100%;
}

/* --------------------------------------------------
   Header
-------------------------------------------------- */

.attribute-filter__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  min-height: 58px;
  padding: 0;
  border: 0;
  background: transparent;
  color: var(--c-text, #1f2937);
  cursor: pointer;
  text-align: right;
}

.attribute-filter__heading {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  min-width: 0;
}

.attribute-filter__name {
  overflow: hidden;
  color: var(--c-text, #1f2937);
  font-size: 13px;
  font-weight: 700;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.attribute-filter__count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 20px;
  height: 20px;
  padding: 0 6px;
  border-radius: 20px;
  background: rgba(200, 169, 106, 0.12);
  color: var(--c-gold, #c8a96a);
  font-size: 10px;
  font-weight: 700;
  line-height: 1;
}

.attribute-filter__icon {
  flex-shrink: 0;
  color: var(--c-text-muted, #94a3b8);
  font-size: 11px;
  transition: transform 0.25s ease;
}

.attribute-filter__icon.is-open {
  transform: rotate(180deg);
}

/* --------------------------------------------------
   Content
-------------------------------------------------- */

.attribute-filter__content {
  overflow: hidden;
  padding-bottom: 16px;
}

.attribute-filter__values {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

/* --------------------------------------------------
   Option
-------------------------------------------------- */

.attribute-option {
  position: relative;
  display: flex;
  align-items: center;
  gap: 10px;
  min-height: 37px;
  margin: 0;
  padding: 7px 8px;
  border-radius: 8px;
  cursor: pointer;
  user-select: none;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.attribute-option:hover {
  background: var(--c-background, #f8fafc);
}

.attribute-option > input {
  position: absolute;
  width: 1px;
  height: 1px;
  opacity: 0;
  pointer-events: none;
}

.attribute-option__checkbox {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  border: 1px solid var(--c-border-strong, #cbd5e1);
  border-radius: 5px;
  background: var(--c-surface, #ffffff);
  color: #ffffff;
  font-size: 9px;
  transition: border-color 0.2s ease, background-color 0.2s ease,
    transform 0.2s ease;
}

.attribute-option__label {
  min-width: 0;
  color: var(--c-text-secondary, #475569);
  font-size: 12px;
  line-height: 1.6;
  transition: color 0.2s ease;
}

.attribute-option.is-selected {
  background: rgba(200, 169, 106, 0.07);
}

.attribute-option.is-selected .attribute-option__checkbox {
  border-color: var(--c-gold, #c8a96a);
  background: var(--c-gold, #c8a96a);
  transform: scale(1.02);
}

.attribute-option.is-selected .attribute-option__label {
  color: var(--c-text, #1f2937);
  font-weight: 600;
}

/* --------------------------------------------------
   Empty state
-------------------------------------------------- */

.attribute-filter__empty {
  padding: 4px 8px 8px;
  color: var(--c-text-muted, #94a3b8);
  font-size: 11px;
}

/* --------------------------------------------------
   Transition
-------------------------------------------------- */

.attribute-collapse-enter-active,
.attribute-collapse-leave-active {
  max-height: 500px;
  opacity: 1;
  transition: max-height 0.25s ease, opacity 0.2s ease;
}

.attribute-collapse-enter-from,
.attribute-collapse-leave-to {
  max-height: 0;
  opacity: 0;
}

/* --------------------------------------------------
   Small screens
-------------------------------------------------- */

@media (max-width: 575.98px) {
  .attribute-filter__header {
    min-height: 54px;
  }

  .attribute-option {
    min-height: 39px;
  }
}
</style>