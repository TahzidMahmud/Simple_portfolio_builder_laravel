<template>
    <div class="container">
            <i class="far fa-bell fa-lg" style="margin-right: 5px;"></i>
          <span class="badge badge-warning navbar-badge">{{ new_count  }}</span>
    </div>
</template>

<script>
    export default {
         props:{
            count:0,
            id:0
        },
        data:()=>({
                    n_count:0,
                }),
        created() {

          this.fetchcount();

        },
        computed: {
    // a computed getter
            new_count: function () {
            // `this` points to the vm instance
            console.log("from" + this.n_count);
              console.log(`${this.count}  and ${  this.n_count}`);
            console.log(this.count + this.n_count);
            return  this.count + this.n_count  ;
            }
        },

        methods:{
            fetchcount(){
                let id_f =parseInt(this.id);
                // setInterval(function(){axios.get(`/count/${id_f}`).then((res)=>{
                //     count = count + res.data;
                // });},5000);




                  window.Echo.private(`contact-mail.${id_f}`)
                    .listen('.contact-mail', (e) => {
                        console.log(e)
                        this.n_count++;
                        this.$alert(e.data);
                        console.log(this.n_count);
                    });

            }
        }
    }
</script>
