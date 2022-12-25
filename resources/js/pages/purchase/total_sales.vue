<template>
    <div>
        <div class="text-left mb-10 d-flex align-center" id="total-header">
            <h3 class="ma-0">Total sales</h3>
            <v-btn white>
                    DAY
            </v-btn>
            <v-btn white>
                    MONTH
            </v-btn>
            <v-btn white>
                    YEAR
            </v-btn>
        </div>
        <div>
            <v-row>
                <v-col>
                    <v-card>
                          <v-sparkline
                            :value="value"
                            height="100"
                            padding="24"
                            stroke-linecap="round"
                            smooth
                            >
                            <template v-slot:label="item">
                                ${{ item.value }}
                            </template>
                        </v-sparkline>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </div>
</template>
<script>
  export default {
    data: () => ({
      value: [
        423,
        446,
        675,
        510,
        590,
        610,
        760,
      ],
    }),
  }
</script>

<style scoped>
    #total-header{
        gap: 50px;
    }
</style>