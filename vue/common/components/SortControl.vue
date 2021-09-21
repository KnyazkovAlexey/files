<template>
    <font-awesome-icon v-if="props.sortable" :icon="icon" class="fa-pull-right"/>
</template>

<script>
import { library } from '@fortawesome/fontawesome-svg-core'
import { faSort, faSortUp, faSortDown } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faSort);
library.add(faSortUp);
library.add(faSortDown);

export default {
    name: 'SortControl',
    props: ['props'],
    components: {FontAwesomeIcon},
    computed: {
        icon() {
            // if not sorted return base icon
            if (!this.props.sortStatus.sorted) return 'sort';

            return this.props.sortStatus.asc ? 'sort-up' : 'sort-down';
        }
    }
}
</script>