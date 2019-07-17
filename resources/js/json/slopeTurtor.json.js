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
|電腦|![computer](https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Gnome-computer.svg/240px-Gnome-computer.svg.png =200x200)| 接收arduino傳出的位移數據|
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
        "src": "hc-sr04-principle.png",
        "title": "超音波原理介紹",
        "content": `聲音在20°C空氣中的傳播速度大約是每秒 344 公尺，傳播速度會受溫度影響，溫度愈高，傳播速度愈快。假設以 344 公尺計算每傳遞1公分所需的時間如下公式：
\`\`\`asciimath
T=1/(344*100)~=2.9*10^-5s=29 us
\`\`\`
<br/>
可知聲音傳播 1 公分所需的時間為 29 微秒(us)。如上圖，由於超音波從發射到返回是兩段距離，因此在計算時必須將結果除以 2 才是正確的物體距離。所以我們可以利用底下的公式算出物體距離(距離單位為公分，其中 Total Time 是測量得到的音波傳播時間):
\`\`\`asciimath
distance (cm) = (Total Time)/(29 * 2)
\`\`\`
<br/>
`
    },
    {
        "src": "arduino-uno.png",
        "title": "Arduino介紹",
        "content": `Arduino為開源硬體產品，它擁有便宜和容易使用的優點。由於開源的緣故，故可以非常容易找到各種感測器或其他電子裝置的函式庫，只需要簡單的調用函式就能驅動，使非專業人士也可輕易使用。`
    },
    {
        "src": "arduino-hcsr04-connect.png",
        "title": "Arduino與超音波(HC-SR04)接線",
        "content": `
如上圖所示為arduino與超音波之接線圖，其中將超音波的echo與trig腳位可以接在任意數位腳位(圖片中紅框框起部分0~13)。
|HC-SR04|Arduino            |
|-------|-------------------|
|Vcc    |+5V                |
|trig   |2 (任意Digital Pin)|
|echo   |3 (任意Digital Pin)|
|GND    |GND                |`
    },
    {
        "src": "slope-sim-system.png",
        "title": "斜坡實驗系統架構",
        "content": `
使用超音波來量測滑車下滑過程中距離的變化，透過Arduino驅動超音波並將時間差換算成距離，最後Arduino透過串列方式將資料傳給電腦，而電腦使用監聽串列埠的程式來擷取數據。
`
    }
]
