<template>
    <input 
        type="submit" 
        class="btn btn-danger mr-1 d-block mb-2 w-100" value="Eliminar"
        @click="eliminiarReceta"   
    >   
</template>

<script>
    export default {
        props: ['recetaId'],
        mounted() {
            //console.log('Eliminando', this.recetaId)
        },
        methods: {
            eliminiarReceta() {
                this.$swal({
                    title: '¿De eliminar esta receta?',
                    text: "Un vez eliminado no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si!',
                    cancelButtonText: 'No'
                }).then((result) => {
                if (result.value) {
                    const params = {
                        id: this.recetaId
                    }
                    //Enviar peticion al servidor
                    axios.post(`/recetas/${this.recetaId}`, {params, _method: 'delete'})
                    .then(respuesta => {
                        this.$swal({
                            title: 'Receta Eliminada',
                            text: 'Se eliminó la receta',
                            icon: 'success'
                         });
                         //Eliminar receta del DOM
                         this.$el.parentNode.parentNode.parentNode.removeChild(this.$el.parentNode.parentNode);
                    })
                    .catch(error => {
                        console.log(error)
                    })
                }
                })
            }
        }
    }
</script>