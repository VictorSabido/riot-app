<template>
    <div :class="'col '+col">
        <div class="card blue-grey lighten-5">
            <div class="card-content black-text">
                <span class="card-title center"><u>{{ this.leagueName }}</u> <strong>{{ this.league.leaguePoints }}<small>PL</small></strong></span>
                <div class="center profile">
                    <div class="profileIconDiv">
                        <span class="level">{{ this.league.rank }}</span>
                        <img :src="image" alt="Profile Icon" class="profileIcon">
                    </div>
                </div>
                <div class="stats center">
                    <span><strong>WR: {{this.league.winRatio}}%</strong> ({{ this.league.wins }}V - {{ this.league.losses }}L)</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            col: String,
            league: Object,
            image: String
        },
        mounted() {
            console.log(this.league)
        },
        computed: {
            leagueName: function() {
                let solo = 'Solitario'
                let flex = 'Flexible'
                let league = this.league;

                if(this.league.queueType == 'RANKED_SOLO_5x5') {
                    name = flex
                } else {
                    name = solo
                }

                return name;
            }
        }
    }
</script>