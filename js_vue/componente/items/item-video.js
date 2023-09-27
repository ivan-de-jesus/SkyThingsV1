
Vue.component('item-video', {
    props: {
        video: Object
    },
    data: function () {
        return {
            urlObject: null
        };
    },
    methods: {
    },
    mounted: function() {
    },
    created: function () {
        if (this.video.puerto.length > 0) {
            this.urlObject = "http://" + this.video.iphost + ":" + this.video.puerto + "/video";
        } else {
            this.urlObject = "http://" + this.video.iphost + "/video";
        }
    },
    template: [
        '<div class="card">',
            '<img class="card-img-top" v-bind:src="urlObject" alt="No image" />',
            '<div class="card-body>"',
                '<h4>{{ video.etiqueta }}</h4>',
            '</div>',
        '</div>'
    ].join('')
});

/**
 * datos:
 *      etiqueta
 *      ip
 *      puerto
 *      
 */