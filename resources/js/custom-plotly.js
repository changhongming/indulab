// 引入plotly核心庫
var Plotly = require('plotly.js/lib/core');

// 加載需要用到的套件
Plotly.register([
    require('plotly.js/lib/scatter'),
]);

module.exports = Plotly;