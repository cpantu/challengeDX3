<template>
    <div class="container min-vh-50">
        <div class="centered" v-if="loading">
            <div class="lds-spinner" ><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>

        <div class="row mt-3">
            <div class="d-flex justify-content-between">
                <h1>Propiedades</h1>
                <div class="w-25 input-group mt-3 mb-3">
                    <span class="input-group-text" id="search"><i class="fa-solid fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search list..." aria-label="search" aria-describedby="search" v-model="filter" style="border-left:none" v-on:keyup="filterChanged">
                </div>
            </div>
        </div>

        <div class="no-results" v-if="!response.data.length && !loading">
            <h4>No properties were found</h4>
        </div>

        <div class="row mt3" v-if="!loading">
            <div class="property-grid">
                <div class="card" v-for="property in response.data" v-on:click="viewProperty(property.name)">
                    <img :src="property.src" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">{{ property.name }}</h5>
                        <div class="d-flex justify-content-around property-description">
                            <div class="text-center"><i class="fa-solid fa-bed"></i> {{property.bedrooms}}</div>
                            <div class="text-center"><i class="fa-solid fa-bath"></i> {{property.bathroom}}</div>
                            <div class="text-center"><i class="fa-solid fa-microchip"></i> {{property.slot}}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="paginator d-flex justify-content-between pt-3 ps-3 pe-3 mt-5" v-if="response.total > 6 && !loading">
            <div>
                <div class="row per-page">
                    <label for="perPage" class="col-6 col-form-label">Rows per page</label>
                    <div class="col-6">
                        <select class="form-control" id="perPage" v-model="perPage" v-on:change="getData()">
                            <option value="6">6</option>
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="48">48</option>
                        </select>
                        <i class="fa-solid fa-chevron-down "></i>
                    </div>
                </div>
            </div>
            <div class="paginator-controls">
                <span class="me-3"> {{response.from}}-{{response.to}} of {{ response.total }} items</span>
                <i class="fa-solid fa-chevron-left me-3" v-if="response.prev_page_url" v-on:click="getData(response.prev_page_url)"></i>
                <i class="fa-solid fa-chevron-right me-3" v-if="response.next_page_url" v-on:click="getData(response.next_page_url)"></i>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            properties: [],
            filter: '',
            perPage: 6,
            loading: true,
            response: {
                data: [],
            },
        }
    },
    mounted() {
        this.getData();
    },
    methods:  {
        getData(url = null) {
            this.loading = true;
            if (!url) {
                const queryParameters = [
                    `perPage=${this.perPage}`
                ];

                let queryString = '';

                if (this.filter) {
                    queryParameters.push(`cost=${this.filter}`)
                }

                if (queryParameters.length) {
                    queryString = '?' + queryParameters.join('&')
                }

                url = propertiesEndpoint + queryString;
            }

            fetch(url)
                .then(response => {
                    this.loading = false;
                    if (!response.ok) {
                        throw Error('Error obtaining the data.');
                    }
                    return response.json();
                })
                .then(json => { this.response = json })
                .catch(error => alert(error));
        },
        filterChanged() {
            this.getData();
        },
        viewProperty(id) {
            window.location.replace('property/' + id);
        },
    },
}
</script>
