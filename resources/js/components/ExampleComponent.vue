<template>
    <div class="container">
            <i class="far fa-bell fa-lg" style="margin-right: 5px;"></i>
          <span class="badge badge-warning navbar-badge">{{ count  }}</span>
    </div>
</template>

<script>
    export default {
         props:{
            count:0,
            id:0
        },
        mounted() {
          this.fetchcount();
        },
        data:()=>({

        }),
        methods:{
            fetchcount(){
                let id_f =parseInt(this.id);
                setInterval(function(){axios.get(`/count/${id_f}`).then((res)=>{
                    count = count + res.data;
                });},5000);

                //

                //   Echo.private(`contact-mail.${id_f}`)
                //     .listen('contact-mail', (e) => {
                //         this.count++;
                //     });
            }
        }
    }
</script>
