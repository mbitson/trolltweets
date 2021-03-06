import axios from 'axios'
import TinyColor from 'tinycolor2'
import find from 'lodash/find'

function addTicks(data) {
    if(data.length === 0) return false;
    let newData = [];
    let currentMonth = new Date(data[0].x.getTime());
    let lastMonth = new Date(data[data.length-1].x.getTime());
    while(currentMonth<=lastMonth) {
        let foundRecord = find(data, {year: currentMonth.getFullYear(), month: currentMonth.getMonth()});
        if(foundRecord){
            newData.push({
                x: foundRecord.x,
                y: foundRecord.y
            });
        }else{
            newData.push({
                x: new Date(currentMonth.getTime()),
                y: 0
            });
        }
        currentMonth.setMonth(currentMonth.getMonth()+1);
    }
    return newData;
}

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
                color = color.spin(40);
                colors.push(color.toString());
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
                let color = TinyColor('rgba(233,30,99, 0.4)');
                categories.forEach(function(category){
                    byCategory[category].forEach(function(month){
                        month.x = new Date(month.year, month.month);
                        month.y = month.count;
                    });
                    color = color.spin(40);
                    chartConfig.push({
                        label: category,
                        backgroundColor: color.toString(),
                        borderColor: color.toString(),
                        data: addTicks(byCategory[category]),
                        lineTension: 0,
                    });
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