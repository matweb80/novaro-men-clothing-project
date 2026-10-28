<template>
  <teleport to="body">
    <transition name="nv-drawer">
      <div v-if="show" class="nv-mobile-menu" @keydown.esc="close" @keydown.tab="trapFocus">
        <button class="nv-mobile-menu__backdrop" type="button" aria-label="بستن منو" @click="close" />
        <aside ref="drawer" class="nv-mobile-menu__drawer" role="dialog" aria-modal="true" aria-label="منوی اصلی">
          <header><base-logo /><button ref="closeButton" type="button" aria-label="بستن منو" @click="close"><font-awesome-icon icon="fa-solid fa-xmark" /></button></header>
          <nav aria-label="منوی موبایل"><a href="/">جدیدترین‌ها</a><a href="/products">پوشاک</a><a href="/products">کالکشن‌ها</a><a href="/products">فروش ویژه</a></nav>
          <details><summary>دسته‌بندی‌ها <font-awesome-icon icon="fa-solid fa-chevron-down" /></summary><div class="nv-mobile-menu__categories"><categories-accordion :categories="categories" /></div></details>
          <footer><a :href="accountHref"><font-awesome-icon icon="fa-regular fa-user" /> {{ accountLabel }}</a><a href="/cart"><font-awesome-icon icon="fa-solid fa-bag-shopping" /> سبد خرید</a></footer>
        </aside>
      </div>
    </transition>
  </teleport>
</template>

<script>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useCheckAppData } from "../../hooks/useCheckAppData";
import CategoriesAccordion from "../header/CategoriesAccordion.vue";
export default {
  components: { CategoriesAccordion },
  props: { show: { type: Boolean, required: true } }, emits: ["close"],
  setup(props, { emit }) {
    const drawer = ref(null); const closeButton = ref(null); const categories = ref([]); const { isAuthenticated } = useCheckAppData();
    const close = () => emit("close");
    const trapFocus = (event) => { const focusable = drawer.value?.querySelectorAll("a[href], button:not([disabled]), summary"); if (!focusable?.length) return; const first = focusable[0], last = focusable[focusable.length - 1]; if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); } else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); } };
    watch(() => props.show, async (visible) => { document.body.style.overflow = visible ? "hidden" : ""; if (visible) { await nextTick(); closeButton.value?.focus(); } });
    onMounted(async () => { try { const response = await fetch("/api/categories"); if (response.ok) categories.value = await response.json(); } catch (error) { console.error("Unable to load categories", error); } });
    onBeforeUnmount(() => { document.body.style.overflow = ""; });
    return { drawer, closeButton, categories, close, trapFocus, accountHref: computed(() => isAuthenticated.value ? "/profile" : "/login"), accountLabel: computed(() => isAuthenticated.value ? "حساب کاربری" : "ورود / عضویت") };
  },
};
</script>

<style scoped>
.nv-mobile-menu { position: fixed; inset: 0; z-index: var(--nv-z-mobile-nav); font-family: var(--nv-font-sans); }
.nv-mobile-menu__backdrop { position: absolute; inset: 0; border: 0; background: rgb(15 23 42 / .5); }
.nv-mobile-menu__drawer { position: relative; display: flex; flex-direction: column; inline-size: min(88vw, 23rem); block-size: 100%; overflow: auto; padding: var(--nv-space-5); background: var(--nv-surface); }
header { display: flex; justify-content: space-between; align-items: center; padding-block-end: var(--nv-space-5); border-block-end: 1px solid var(--nv-border); }
header button { display: grid; place-items: center; inline-size: 2.75rem; block-size: 2.75rem; border: 0; background: transparent; color: var(--nv-primary); cursor: pointer; }
nav { display: grid; padding-block: var(--nv-space-4); }
nav a, footer a, .nv-mobile-menu__categories a { padding-block: var(--nv-space-3); color: var(--nv-text); }
details { border-block: 1px solid var(--nv-border); }
summary { display: flex; justify-content: space-between; align-items: center; min-block-size: 3.5rem; cursor: pointer; color: var(--nv-primary); font-weight: 600; }
summary svg { font-size: .7em; } details[open] summary svg { transform: rotate(180deg); }
.nv-mobile-menu__categories { display: grid; padding-block-end: var(--nv-space-3); padding-inline-start: var(--nv-space-3); }
footer { display: grid; gap: var(--nv-space-2); margin-block-start: auto; padding-block-start: var(--nv-space-5); }
footer a { display: flex; align-items: center; gap: var(--nv-space-3); }
.nv-drawer-enter-active, .nv-drawer-leave-active { transition: opacity var(--nv-duration-normal) var(--nv-ease-out); } .nv-drawer-enter-active .nv-mobile-menu__drawer, .nv-drawer-leave-active .nv-mobile-menu__drawer { transition: transform var(--nv-duration-normal) var(--nv-ease-out); } .nv-drawer-enter-from, .nv-drawer-leave-to { opacity: 0; } .nv-drawer-enter-from .nv-mobile-menu__drawer, .nv-drawer-leave-to .nv-mobile-menu__drawer { transform: translateX(100%); }
</style>
