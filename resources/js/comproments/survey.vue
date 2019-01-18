<template>
    <div>
        <survey :survey='survey'/>
        <div id="surveyResult"></div>
    </div>
</template>
<script>
'use strict';
// 表單函式庫
import * as Survey from "survey-vue";
// 將字串解析為html字串
import * as showdown from "showdown";
// 組合html函式庫
import * as md from "../md-combine";

// surveyjs使用bootstrap風格
Survey
    .StylesManager
    .applyTheme("bootstrap");

// surveyjs自定義css
var myCss = {
    // 按鈕風格
    navigationButton: "button btn-primary",
    // 標頭
    header: "panel-heading card-header border-success",
    // 內文
    body: "panel-body card-block bg-white",
    // 結尾
    footer: "card-footer bg-white border-success panel-footer card-footer"
};

// 表單json
var json = {
    title: "**斜坡運動**",
    showProgressBar: "bottom",
    showTimerPanel: "top",
    firstPageIsStarted: true,
    startSurveyText: "開始測驗",
    pages: [
        {
            questions: [
                {
                    type: "text",
                    name: "question3",
                    title: "姓名",
                    isRequired: true
                },
                {
                    type: "text",
                    name: "question4",
                    title: "學校",
                    isRequired: true
                },
                {
                    type: "text",
                    name: "question5",
                    title: "學號",
                    isRequired: true
                }
            ]
        },
        {
            questions: [
                {
                    type: "radiogroup",
                    name: "question1",
                    title: `今有一電動車綁著紙帶，${md.strongUnderline('若想從打點計時器在紙帶造成的點距變化', '#ff0000')}(如下圖)，判斷電動車的運動情形，下列何者${md.color(md.strongUnderline('最不可能'), 'Red')}是此實驗之相關變因(應記錄之數據)？`
                    //title: "今有一電動車綁著紙帶，**<u>若想從打點計時器在紙帶造成的點距變化</u>**(如下圖)，判斷電動車的運動情形，下列何者**<u><font color=\"red\">最不可能</font></u>**是此實驗之相關變因(應記錄之數據)？"
                    ,
                    isRequired: true,
                    choices: [
                        {
                            value: "item1",
                            text: "x(電動車的位置變化)"
                        },
                        {
                            value: "item2",
                            text: "t(電動車的運動時間)"
                        },
                        {
                            value: "item3",
                            text: "m(電動車的質量大小)"
                        }

                    ]
                },
                {
                    type: "html",
                    name: "info",
                    html: "<table><body><row><td><img src='/image/01.png' width='95%' /></td></row></body></table>"
                }
            ]

        }
    ]
};

// 設定表單的問題
var survey = new Survey.Model(json);

// 完成表單提交觸發
survey
    .onComplete
    .add(function (result) {
        document
            .querySelector('#surveyResult')
            .innerHTML = "result: " + JSON.stringify(result.data);
    });

// 使用showdown來進行markdwon標籤處理轉換為html字串
var converter = new showdown.Converter();
// 停止解譯下劃線如__
converter.setOption('literalMidWordUnderscores', true)
// 設定surveyjs外掛showdwon套件
survey
    .onTextMarkdown
    .add(function (survey, options) {
        // 內部字串轉為html格式字串
        var str = converter.makeHtml(options.text);
        // 移除跟段落字符 <p></p>
        //str = str.substring(3);
        str = str.substring(3, str.length - 4);
        // 更新html
        options.html = str;
    });

survey.css = myCss;

export default {
  data() {return {survey: survey}}
}
</script>
