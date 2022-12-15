<template>
    <li class="list-group-item">
        {{ name }}駅
        <div class="float-right" @click="remove" style="cursor: pointer;"><a href="#">削除</a></div>
        <!-- input type=hiddenの設定 -->
        <input type="hidden" name="stations[]" :value="id" />
    </li>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted. companySations')
    },
    props: ['id', 'group_code', 'name', 'railroad'],
    methods: {
        remove: function(index) {
            // templateでのクリックイベント時の関数removeを、親で検知可能にする
            this.$emit('remove')
        }
    }
}
</script>
