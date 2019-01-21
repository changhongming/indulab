import Handsontable from 'handsontable';
import 'handsontable/dist/handsontable.full.css';

import Plotly from './custom-plotly';

// 預留欄位的內容
const tpl = ["", ""]

// 取得DOM物件
const editTable = document.getElementById('editTable');
const drawButton = document.getElementById('drawButton');
const clearButton = document.getElementById('clearButton');

// 畫面加載完成
window.onload = function () {
    Plotly.newPlot('plotDiv', [], layout, { displaylogo: false });
    // 清除loading畫面
    document.querySelector(".loading").remove();
}

// 清除按鈕事件
const clearData = function () {
    /** 因使用loadData([])清空資料會把整個欄位變不見，目前將DOM物件清空在重新創建一個 **/
    // 清空原本的圖表DOM
    hot1.destroy();
    // 新增一個
    hot1 = new Handsontable(editTable, configTable);
}

// 繪圖按鈕事件
const drawData = function () {
    // 取得表格資料
    data.x = hot1.getDataAtCol(0);
    data.y = hot1.getDataAtCol(1);

    // 清除最後一列空的輸入框資料
    data.x.length -= 1;
    data.y.length -= 1;

    console.log(data);

    Plotly.newPlot('plotDiv', [data], layout, { displaylogo: false });
}

// 註冊按鈕事件
clearButton.onclick = clearData;
drawButton.onclick = drawData;

// 繪圖資料
const data = { x: [], y: [], mode: 'markers' };
const layout = {
    margin: {
        t: 15,
        l: 30,
        r: 50,
        b: 100,
        pad: 4
    },
    xaxis: {
        // 左下從0/0顯示
        rangemode: 'tozero',
        autorange: true,
    },
    yaxis: {
        // 左下從0/0顯示
        rangemode: 'tozero',
        autorange: true,
    },
    // 將plotly的背景改為透明
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)'
};

// 判斷是否為空列
function isEmptyRow(instance, row) {
    let rowData = instance.countRows();

    for (let i = 0, ilen = rowData.length; i < ilen; i++) {
        if (rowData[i] !== null) {
            return false;
        }
    }

    return true;
}

// 預留欄位渲染事件
function defaultValueRenderer(instance, td, row, col, prop, value, cellProperties) {
    const args = arguments;

    if (args[5] === null && isEmptyRow(instance, row)) {
        args[5] = tpl[col];
        td.style.color = '#999';
    }
    else {
        td.style.color = '';
    }
    Handsontable.renderers.TextRenderer.apply(this, args);
}

// 表格設定及事件註冊
const configTable = {
    startRows: 1,
    startCols: 2,
    minSpareRows: 1,
    colHeaders: ['X', 'Y'],
    contextMenu: true,
    cells: function (row, col, prop) {
        let cellProperties = {};
        cellProperties.renderer = defaultValueRenderer;

        return cellProperties;
    },
    beforeChange: function (changes) {
        const instance = hot1;
        const ilen = changes.length;
        const clen = instance.countCols();
        let rowColumnSeen = {};
        let rowsToFill = {};
        let i;
        let c;

        for (i = 0; i < ilen; i++) {
            if (changes[i][2] === null && changes[i][3] !== null) {
                if (isEmptyRow(instance, changes[i][0])) {
                    // add this row/col combination to cache so it will not be overwritten by template
                    rowColumnSeen[changes[i][0] + '/' + changes[i][1]] = true;
                    rowsToFill[changes[i][0]] = true;
                }
            }
        }
        for (let r in rowsToFill) {
            if (rowsToFill.hasOwnProperty(r)) {
                for (c = 0; c < clen; c++) {
                    // if it is not provided by user in this change set, take value from template
                    if (!rowColumnSeen[r + '/' + c]) {
                        changes.push([r, c, null, tpl[c]]);
                    }
                }
            }
        }
    }
};

let hot1 = new Handsontable(editTable, configTable);

