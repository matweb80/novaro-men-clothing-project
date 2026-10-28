<script setup>
import { inject, ref, nextTick } from "vue";

const props = defineProps({
  items: { type: Array, required: true },
  depth: { type: Number, default: 0 },
  path: { type: Array, default: () => [] },
});

// Shared open-path state, provided once by DesktopNavigation and read/written
// by every NavLevel at every depth — a single flat array of ids from the
// root to whichever item is currently open replaces the prototype's
// closeAllNavItems() DOM-class sweep.
const openPath = inject("desktopNavOpenPath");

function hasChildren(item) {
  return !!(item.child && item.child.length);
}

function isOpen(item) {
  return openPath.value[props.depth] === item.id;
}

function openItem(item) {
  openPath.value = [...props.path, item.id];
}

function closeItem(item) {
  if (openPath.value[props.depth] === item.id) {
    openPath.value = props.path.slice();
  }
}

const flyoutEls = {};
function setFlyoutRef(id, componentInstance) {
  if (componentInstance) flyoutEls[id] = componentInstance.$el;
  else delete flyoutEls[id];
}

function onKeydown(e, item) {
  if (e.key === "Escape") {
    closeItem(item);
    e.target.focus();
  }
  if (e.key === "ArrowDown") {
    e.preventDefault();
    openItem(item);
    nextTick(() => {
      const first = flyoutEls[item.id]?.querySelector("a,button");
      first?.focus();
    });
  }
}
</script>

<template>
  <ul
    class="nav-level nav-flyout"
    :data-depth="depth"
    role="list"
  >
    <li
      v-for="item in items"
      :key="item.id"
      class="nav-item"
      :class="{ 'is-open': isOpen(item) }"
      @mouseenter="hasChildren(item) && openItem(item)"
      @mouseleave="hasChildren(item) && closeItem(item)"
    >
      <a
        v-if="!hasChildren(item)"
        :href="`/categories/${item.slug}`"
        class="nav-link"
        >{{ item.name }}</a
      >

      <template v-else>
        <button
          type="button"
          class="nav-link"
          :id="`navbtn-${item.id}`"
          aria-haspopup="true"
          :aria-expanded="isOpen(item) ? 'true' : 'false'"
          :aria-controls="`navpanel-${item.id}`"
          @click.stop="openItem(item)"
          @keydown="onKeydown($event, item)"
        >
          <span>{{ item.name }}</span>
          <font-awesome-icon icon="fa-solid fa-chevron-right" class="chev" />
        </button>
        <NavLevel
          :ref="(el) => setFlyoutRef(item.id, el)"
          v-if="isOpen(item)"
          :id="`navpanel-${item.id}`"
          :aria-labelledby="`navbtn-${item.id}`"
          :items="item.child"
          :depth="depth + 1"
          :path="[...path, item.id]"
        />
      </template>
    </li>
  </ul>
</template>
