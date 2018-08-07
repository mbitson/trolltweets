import axios from 'axios'
import TinyColor from 'tinycolor2'

export default {

    namespaced: true,

    state: {
        status: '',
        top: [],
        hashtags: [],
        summary: [],
        categoryTotals: [],
        count: 0
    },

    getters: {
        getHashtags: state => {
            return state.hashtags;
        },
        getCount: state => {
            return state.count;
        },
        getTop: state => {
            return state.top;
        },
        getTopCount: state => {
            if(typeof state.top[0] === "undefined") return false;
            return state.top[0].count;
        },
        getCategoryTotal: state => {
            let total = 0;
            state.categoryTotals.forEach(function(category){
                total+=category.count;
            });
            return total;
        },
        getCategoryTotals: state => {
            return state.categoryTotals;
        },
        getCategoryTotalsForChart: state => {
            if(state.categoryTotals.length < 1) return false;
            let dataset = [];
            let labels = [];
            let colors = [];
            let color = TinyColor('rgba(233,30,99, 0.6)');
            state.categoryTotals.forEach(function(category){
                dataset.push(category.count);
                labels.push(category.account_category);
                colors.push(color.toString());
                color = color.spin(40);
            });
            return {
                datasets: [
                    {
                        data:dataset,
                        backgroundColor: colors,
                        borderWidth: 1,
                        borderColor: TinyColor('rgba(0,0,0,0.1)').toString()
                    }
                ],
                labels
            };
        },
        getSummaryDataForChart: state => {
            let chartConfig = [];

            if(typeof state.summary === 'undefined'){
                return false;
            }

            let byCategory = {};
            let categories = [];
            if(typeof state.summary['by_category'] !== 'undefined') {
                state.summary['by_category'].forEach(function (record) {
                    if (typeof byCategory[record.account_category] === 'undefined') {
                        byCategory[record.account_category] = [];
                        categories.push(record.account_category);
                    }
                    byCategory[record.account_category].push(record);
                });
                let color = TinyColor('rgba(233,30,99, 0.6)');
                categories.forEach(function(category){
                    byCategory[category].forEach(function(month){
                        month.x = new Date(month.year, month.month);
                        month.y = month.count;
                    });
                    color = color.spin(40);
                    chartConfig.push({
                        label: category,
                        backgroundColor: color.toString(),
                        data: byCategory[category],
                        lineTension: 0
                    });
                });
            }

            if(typeof state.summary['by_month'] !== 'undefined'){
                state.summary['by_month'].forEach(function(month){
                    month.x = new Date(month.year, month.month);
                    month.y = month.count;
                });
                chartConfig.push({
                    label: 'All Categories',
                    backgroundColor: 'rgba(233,30,99, 0.6)',
                    data: state.summary['by_month'],
                    lineTension: 0
                });
            }
            return chartConfig;
        }
    },

    mutations : {
        request: (state) => {
            state.status = 'loading';
        },
        error: (state) => {
            state.status = 'error';
        },
        indexSuccess: (state, hashtags) => {
            state.status = 'success';
            state.hashtags = hashtags;
        },
        topSuccess: (state, hashtags) => {
            state.status = 'success';
            state.top = hashtags;
        },
        summarySuccess: (state, summary) => {
            state.status = 'success';
            state.summary = summary;
        },
        countSuccess: (state, count) => {
            state.status = 'success';
            state.count = count;
        },
        categoryTotalSuccess: (state, categoryTotals) => {
            state.status = 'success';
            state.categoryTotals = categoryTotals;
        },
        success: (state) => {
            state.status = 'success';
        },
    },

    actions: {

        index: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags', method: 'GET' })
                    .then(resp => {
                        const hashtags = resp.data;
                        commit('indexSuccess', hashtags);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

        summary: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags/summary', method: 'GET' })
                    .then(resp => {
                        const summary = resp.data;
                        commit('summarySuccess', summary);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

        top: ({commit, dispatch}, limit) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags/top/'+limit, method: 'GET' })
                    .then(resp => {
                        const hashtags = resp.data;
                        commit('topSuccess', hashtags);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

        count: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags/count', method: 'GET' })
                    .then(resp => {
                        commit('countSuccess', resp.data);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

        categoryTotals: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags/categoryTotals', method: 'GET' })
                    .then(resp => {
                        commit('categoryTotalSuccess', resp.data);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

    }
}