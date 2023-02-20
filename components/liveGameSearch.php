<div class="container">
    <select name="searchGameSeasonSelect" id="searchGameSeasonSelect">
        <option value="1" selected>2022/2023</option>
        <option value="2">2023/2024</option>
        <option value="3">2024/2025</option>
        <option value="4">2025/2026</option>
        <option value="5">2026/2027</option>
        <option value="6">2027/2028</option> 
    </select>
    <input type="hidden" id="hiddenSelectedSeason" name="hiddenSelectedSeason">
    <?php
require '../components/addGameButton.php';
?>
</div>

<div class="mt-3" id="searchGameResult"></div>

