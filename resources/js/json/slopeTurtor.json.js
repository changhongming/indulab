export default [
    {
        "src": "galileo-slope.jpg",
        "title": "實驗介紹",
        "content": "在一軌道斜面上放上一滑車或一圓球，在**不考慮摩擦力**的情況下進行實驗，藉此觀察斜面在不同**角度**及滑動物體**質量**對於**位移**、**速度**及**加速度**間的影響。"
    },
    {
        "src": "",
        "title": "實驗器材",
        "content": `
| 器材名稱 | 示意圖 | 說明 |
| -------- | -------- | -------- |
| 滑軌跑道  | ![slider](https://upload.wikimedia.org/wikipedia/commons/f/fc/Slide_in_Parque.jpg =200x160)| 用於斜坡供滑車滑動 |
| 滑車| ![car](/image/small-car-20190223.jpg =200x160) | 滑車在滑軌跑道上滑動 |
|超音波模組|![hc-sr04.png](/image/hc-sr04.png =200x160)|量測滑車的位移變化|
|Arduino|![arduino-uno.png](/image/arduino-uno.png =200x160)|驅動超音波模組並傳送資料給電腦|
|電腦|![](https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Gnome-computer.svg/240px-Gnome-computer.svg.png =200x200)| 接收arduino傳出的位移數據|
`
    },
    {
        "src": "hc-sr04.png",
        "title": "超音波感測模組(HC-SR04)規格",
        "content": `
- 電源：DC5V/2mA
- 工作電壓：5V
- 精度：3mm
- 距離範圍：2 ~ 450cm
- 有效的角度：<15°`
    },
    {
        "src": "arduino-uno.png",
        "title": "Arduino介紹",
        "content": `Arduino為開源硬體產品，它擁有便宜和容易使用的優點。由於開源的緣故，故可以非常容易找到各種感測器或其他電子裝置的函式庫，只需要簡單的調用函式就能驅動，使非專業人士也可輕易使用。`
    },
    {
        "src": "",
        "title": "Arduino與超音波(HC-SR04)接線",
        "content": `
|HC-SR04|Arduino            |
|-------|-------------------|
|Vcc    |+5V                |
|trig   |2 (任意Digital Pin)|
|echo   |3 (任意Digital Pin)|
|GND    |GND                |`
    },
    {
        "src": "",
        "title": "",
        "content": ""
    },
    {
        "src": "",
        "title": "",
        "content": ""
    },
    {
        "src": "",
        "title": "",
        "content": ""
    }
]
