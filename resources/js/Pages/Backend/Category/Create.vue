<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "Categories",
        icon: "view-list",
        bc: [
            {label: 'procedures'}, {
                label: "categories",
                route: "admin.procedures.categories.index"
            }, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import {__} from "@core/Mixins/translations";
import {Link, useForm} from "@inertiajs/vue3";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import Input from "@core/Components/Form/Input.vue";
import {ref} from "vue";
import {useStore} from "vuex";

const props = defineProps(["parents", "parent"])

const selectedLocale = ref();

const form = useForm({
    parent_id: props.parent?.id || null,
    name: null,
});

const store = useStore();

function submit() {
    form.post(route("admin.procedures.categories.store"), {
        preserveScroll: true, preserveState: true,
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
}
</script>

<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base ">
                <div class="col-span-1">
                    <p class="text-sm"
                       v-html="__('create_summary')"></p>
                </div>
                <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        rounded-lg
                        shadow">
                    <div class="grid grid-cols-6 p-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <span class="font-semibold" v-if="parents.length">{{__('subcategory_of')}}:</span>
                            <ul class="mb-2 flex" v-if="parents.length">
                                <li class="text-sm" v-for="(parent, index) in parents" :key="parent.id" >
                                    <Link :href="route('admin.procedures.categories.edit', {category: parent.id})">
                                        {{ parent.name }} <span class="px-2" v-if="(index + 1) < parents.length">/</span>
                                    </Link>
                                </li>
                            </ul>
                            <Input v-model="form"
                                   v-model:locale="selectedLocale"
                                   :errors="form.errors"
                                   :label="__('name')"
                                   autofocus
                                   name="name"
                                   required/>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                            <SaveFormButton :form="form"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<style scoped>

</style>
