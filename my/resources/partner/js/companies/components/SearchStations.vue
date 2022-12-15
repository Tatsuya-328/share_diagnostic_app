<template>
	<ul class="list-group">
        <a class="list-group-item" @click="add" style="cursor: pointer;" >{{ railroad.name }}{{ name }}駅</a>
    </ul>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted. searchSations')
    },
    props: ['name', 'railroad'],
    methods: {
        add: function(station) {
            // templateでのクリックイベント時の関数removeを、親で検知可能にする
            this.$emit('add')
        }
    }
}
</script>