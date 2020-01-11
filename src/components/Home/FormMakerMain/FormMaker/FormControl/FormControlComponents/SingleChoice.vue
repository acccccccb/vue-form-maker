<template>
  <div>
      <el-radio-group :name="data.name" :placeholder="data.notes" v-model="data.value" v-if="data.type=='single_choice'">
          <div v-for="(options,index) in data.choices" :key="index"><el-radio :label="options.name">{{options.value}}</el-radio></div>
      </el-radio-group>
  </div>
</template>
<script>

  export default {
    name:'SingleChoice',
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