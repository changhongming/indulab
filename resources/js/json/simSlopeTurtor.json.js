export default [
    {
        "src": "sim-slope-tutor.png",
        "title": "佈局功能說明",
        "content": `
| 佈局編號 | 佈局名稱 | 佈局功能說明 |
| -------- | -------- | -------- |
|1|實驗變因控制|  用於調整實驗參數，會即時對應到**編號3**的模擬畫面。 |
|2|動畫及輔助格線控制| 用於控制**編號3**模擬畫面。 |
|3|模擬動畫|在**編號2**按下start按紐時會開始演繹動畫，並且會即時更新**編號5**的數據資料。|
|4|操作歷程|每當**編號3**的動畫完成，就會將最終的結果記錄在此處。|
|5|即時數據監控(可拖曳)|會即時顯示**編號3**目前動畫的參數，如加速度、位移、瞬時速度、經過時間。|`
    },
    {
        "src": "sim-slope-tutor-01.png",
        "title": "實驗變因控制介紹",
        "content": `
### **說明**
你可以任意調整輸入框的數值，只要輸入得數值在範圍內就會套用到動畫設定。除了可以使用鍵盤直接輸入數值外，也可透過**鍵盤上下鍵及滑鼠滾輪調整**。編輯完成後只要按下**鍵盤Enter**或是**離開編輯**就會立即套用，如果使用上下鍵或是滑鼠滾輪編輯

* 下圖是進入編輯時會提醒允許輸入之範圍：
![sim-slope-tutor-01-04.png](/image/sim-slope-tutor-01-04.png)

* 下圖是輸入數值在範圍內(代表**成功**設定變因)：
![sim-slope-tutor-01-05.png](/image/sim-slope-tutor-01-05.png)

* 下圖是輸入數值在範圍外(代表設定變因**沒有**套用，會套用**最後一次**成功設定之參數)
![sim-slope-tutor-01-06.png](/image/sim-slope-tutor-01-06.png)

### **變因對應表**

| 變因名稱 | 變因圖片 | 變因單位 |
| -------- | -------- | -------- |
| 滑軌長度  | ![sim-slope-tutor-01-01.png](/image/sim-slope-tutor-01-01.png)| 公尺(m)|
| 滑車(方塊)重量| ![sim-slope-tutor-01-02.png](/image/sim-slope-tutor-01-02.png) | 公斤(kg) |
|滑軌與水平面的夾角|![sim-slope-tutor-01-03.png](/image/sim-slope-tutor-01-03.png)|度(deg)|
`
    },
    {
        "src": "sim-slope-tutor-02-01.png",
        "title": "動畫及輔助格線控制介紹",
        "content": `
### **說明**
你可以透過這個表單控制你的動畫，當按下start按鈕時會開始動畫；並且原本的start按鈕會以pause代替；當你再次按下pause則動畫會暫停；等到下次按下start再重新開始。你隨時都可以按下reset去重置你的動畫。
> 當你開始動畫時就**無法變更實驗變因**，除非你按下**reset按鈕**或是等待**動畫撥放完成**。

網格線可以輔助你計算位置，每格的單位為**10公分**，你可以選擇是否開啟，以及使用與斜面平行垂直線或一般網格線。

> download 可以下載模擬數據，目前為**測試階段**。

<br />
`
    },
    {
        "src": "slope_sim.png",
        "title": "模擬動畫",
        "content": `
此畫面會演示斜坡實驗的動畫，你可以藉由此畫面來觀察物體滑動的變化。
`
    },
    {
        "src": "sim-slope-tutor-04-01.png",
        "title": "操作歷程介紹",
        "content":
`
每個完成的動畫都會紀錄在此處，此表格會記錄下斜坡角度、質量、結束的瞬時速度、結束位移、結束時間等資訊，你可以觀察在不同變因條件下對於結果的影響，此表格提供**排序**及**篩選**功能。
`
    },
    {
        "src": "sim-slope-tutor-05-01.png",
        "title": "即時數據監控介紹",
        "content":
`
此視窗提供動畫當前及時數據的顯示，你可以隨時拖曳此視窗，也可將其縮小，只需在此視窗點一下滑鼠左鍵即可；在點一下即可復原，縮小的圖示如下:

![sim-slope-tutor-04-01.png](/image/sim-slope-tutor-05-02.png)
`
    }
]
