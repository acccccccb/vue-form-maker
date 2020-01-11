<template>
  <div>
      <el-select :name="data.name" :placeholder="data.notes" v-model="data.value" v-if="data.type=='select_choice'">
          <el-option v-for="(options,index) in data.choices" :key="index" :label="options.value" :value="options.value"></el-option>
      </el-select>
  </div>
</template>
<script>

  export default {
    name:'SelectChoice',
      props:{
        data:{
            require:true
        }
      },
    data(){
      return {

      }
    },
    methods:{

    }
  }
</script>
<style scoped>

</style>