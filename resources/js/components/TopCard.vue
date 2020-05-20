<template>
    <div :class="'col '+col">
        <div class="card blue-grey lighten-5">
            <div class="card-content black-text">
                <span class="card-title center"><u>{{ this.leagueName }}</u> <strong>{{ this.leaguePoints }}<small>PL</small></strong></span>
                <div class="center profile">
                    <div class="profileIconDiv">
                        <span class="level">{{ level }}</span>
                        <img :src="image" alt="Profile Icon" class="profileIcon">
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            col: String,
            league: String,
            image: String,
            level: String,
            leaguePoints: String,
        },
        mounted() {
            console.log(this.league)
        },
        computed: {
            leagueName: function() {
                let solo = 'Solitario'
                let flex = 'Flexible'
                let league = this.league;

                if(league == 'RANKED_SOLO_5x5') {
                    name = flex
                } else {
                    name = solo
                }

                return name;
            }
        }
    }
</script>