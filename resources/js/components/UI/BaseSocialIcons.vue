<template>
  <a href="#" class="icon-btn" aria-label="نوواررو در تلگرام">
    <font-awesome-icon
      icon="fa-brands fa-telegram"
      class="brand-icon"
    />
  </a>
  <a href="#" class="icon-btn" aria-label="نوواررو در واتساپ">
    <font-awesome-icon
      icon="fa-brands fa-whatsapp"
      class="brand-icon"
    />
  </a>
  <a href="#" class="icon-btn" aria-label="نوواررو در اینستاگرام">
    <font-awesome-icon
      icon="fa-brands fa-instagram"
      class="brand-icon"
    />
  </a>
  <a href="#" class="icon-btn" aria-label="نوواررو در یوتیوب">
    <font-awesome-icon
      icon="fa-brands fa-youtube"
      class="brand-icon"
    />
  </a>
  <a href="#" class="icon-btn" aria-label="نوواررو در تریدز">
    <font-awesome-icon
      icon="fa-brands fa-threads"
      class="brand-icon"
    />
  </a>
</template>

<style scoped>
.icon-btn:hover{
  background: transparent;
}
.brand-icon {
  font-size: 32px;
  transition: all 0.3s ease-out;
  color: var(--c-text-muted);
}
.brand-icon:hover {
  color: var(--primary-color) !important;
}

@media (max-width: 768px) {
  .brand-icon {
    font-size: 40px;
  }
}
</style>