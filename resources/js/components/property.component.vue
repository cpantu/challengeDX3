<template>
    <div class="container min-vh-50">
        <div class="centered" v-if="loading">
            <div class="lds-spinner" ><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>

        <div class="row mt-3" v-if="!loading">
            <div class="d-flex justify-content-between">
                <h1>{{ property.name }}</h1>
                <h2>$ {{ property.cost }}</h2>
            </div>
        </div>

        <div class="row mt3" v-if="!loading">

            <img :src="property.src" class="img-main">

            <div class="property-details">
                <div class="property-details-header">
                    Details
                </div>

                <div class="d-flex justify-content-around property-details-rows">
                    <div class="text-center"><i class="fa-solid fa-bed"></i> {{property.bedrooms}}</div>
                    <div class="text-center"><i class="fa-solid fa-bath"></i> {{property.bathrooms}}</div>
                    <div class="text-center"><i class="fa-solid fa-microchip"></i> {{property.slot}}</div>
                    <div class="text-center"><i class="fa-solid fa-home"></i> {{property.mts}}</div>
                    <div class="text-center"><i class="fa-solid fa-calendar"></i> {{property.year}}</div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['id'],
    data() {
        return {
            property: {},
            loading: true,
        }
    },
    mounted() {
        this.getProperty();
    },
    methods:  {
        getProperty() {
            fetch(propertiesEndpoint + '?name=' + this.id)
                .then(response => {
                    this.loading = false;
                    if (!response.ok) {
                        throw Error('Error obtaining the data. ');
                    }
                    return response.json();
                })
                .then(json => { this.property = json.data[0]; })
                .catch(error => alert(error));
        },
    },
}
</script>
