document.addEventListener('DOMContentLoaded', function() {
    const rankBtn = document.getElementById('userRankBtn');
    const infoPanel = document.getElementById('userInfoPanel');
    
    if (!rankBtn || !infoPanel) return;
    
    rankBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        infoPanel.classList.toggle('show');
        rankBtn.classList.toggle('active');
    });
    
    document.addEventListener('click', function(e) {
        if (!rankBtn.contains(e.target) && !infoPanel.contains(e.target)) {
            infoPanel.classList.remove('show');
            rankBtn.classList.remove('active');
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && infoPanel.classList.contains('show')) {
            infoPanel.classList.remove('show');
            rankBtn.classList.remove('active');
        }
    });
});