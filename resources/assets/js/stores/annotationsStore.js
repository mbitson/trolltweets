import axios from 'axios'
import TinyColor from 'tinycolor2'

function AnnotationsMouseOver(e, element){
    element.options.label.enabled = true;
    element.chartInstance.chart.canvas.style.cursor = 'pointer';
    element.chartInstance.update();
}
function AnnotationsMouseOut(e, element){
    element.options.label.enabled = false;
    element.chartInstance.chart.canvas.style.cursor = 'initial';
    element.chartInstance.update();
}
export default {

    namespaced: true,

    state: {
        annotations: [
            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2016, 10),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "2016 Election Month",
                    enabled: false,
                    position: "top"
                }
            },
            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2016, 6),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "Trump accepts official nomination",
                    enabled: false,
                    position: "top"
                }
            },
            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2016, 2),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "Trump wins 7 states on Super Tuesday",
                    enabled: false,
                    position: "top"
                }
            },

            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2016, 0),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "First Polls Of 2016",
                    enabled: false,
                    position: "top"
                }
            },
            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2017, 0),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "Inaugeration Month",
                    enabled: false,
                    position: "top"
                }
            },
            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2015, 6),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "Trump announces his candidacy",
                    enabled: false,
                    position: "top"
                }
            },
            {
                type: "line",
                mode: "vertical",
                scaleID: "x-axis-0",
                value: new Date(2017, 8),
                borderColor: "red",
                borderDash: [2, 2],
                onMouseover: function(e){
                    AnnotationsMouseOver(e, this);
                },
                onMouseout: function(e) {
                    AnnotationsMouseOut(e, this);
                },
                label: {
                    content: "DACA, Hurricane Maria, 'Rocket Man'",
                    enabled: false,
                    position: "top",
                }
            }
        ]
    },

    getters: {
        getAnnotations: state => {
            return function(){
                return state.annotations;
            }
        }
    },

    mutations : {
    },

    actions: {

    }
}