import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useProductsSort(initialSortColumn, initialSortDirection) {
    const sortColumn = ref(initialSortColumn);
    const sortDirection = ref(initialSortDirection);

    function isSortColumn(column) {
        return sortColumn.value === column;
    }
    function sortBy(column) {
        sortDirection.value =
            sortColumn.value === column
                ? sortDirection.value === "asc"
                    ? "desc"
                    : "asc"
                : "asc";
        sortColumn.value = column;
        router.get(
            route("products.index"),
            {
                ...route().params,
                sort_column: sortColumn.value,
                sort_direction: sortDirection.value,
            },
            { preserveScroll: true }
        );
    }

    return {
        sortColumn,
        sortDirection,
        isSortColumn,
        sortBy,
    };
}
