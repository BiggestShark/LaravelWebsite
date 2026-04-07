import './bootstrap';

/* // 將函式綁定到全域 window 物件，讓 Blade 視圖中的 onclick 可以呼叫到
window.switchOsuMode = function(mode) {
    // 1. 隱藏所有數據網格
    document.querySelectorAll('.osu-stats-grid').forEach(el => {
        el.style.display = 'none';
        el.classList.remove('active');
    });
    
    // 2. 移除所有按鈕的高亮狀態
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // 3. 顯示被點擊的模式數據
    const targetGrid = document.getElementById(mode + '-stats');
    if(targetGrid) {
        targetGrid.style.display = ''; 
        targetGrid.classList.add('active');
    }
    
    // 4. 替當前點擊的按鈕加上高亮狀態 (透過尋找包含該模式名稱的按鈕)
    const targetBtn = document.querySelector(`.tab-btn[onclick*="${mode}"]`);
    if (targetBtn) {
        targetBtn.classList.add('active');
    }
}; */