<script setup lang="ts">
const theme = {
    mode: "light",
};

if (
    window.matchMedia &&
    window.matchMedia("(prefers-color-scheme: dark)").matches
) {
    theme.mode = "dark";
} else {
    theme.mode = "light";
}
</script>

<template>
    <img
        v-if="theme.mode === 'dark'"
        src="/images/logo.png"
        alt="Logo"
        class="w-1/4"
    />
    <img v-else src="/images/logoLightS.png" alt="Logo" class="w-1/4" />
</template>
