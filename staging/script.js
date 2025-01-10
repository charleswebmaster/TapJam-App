const counterElement = document.getElementById('counter');
const walletElement = document.getElementById('wallet');
const dollarImage = document.getElementById('dollarImage');
const progressBar = document.getElementById('progressBar').firstElementChild;
const progressText = document.getElementById('progressText');
const withdrawForm = document.getElementById('withdrawForm');
const withdrawButton = document.getElementById('withdrawButton');
const sendButton = document.getElementById('sendButton');
const withdrawFormElem = document.getElementById('withdrawFormElem');
const levelElement = document.getElementById('level');

let count = parseInt(localStorage.getItem('count')) || 0;
let balance = parseInt(localStorage.getItem('balance')) || 0;
let taps = parseInt(localStorage.getItem('taps')) || 0;
let tapValue = 1;
let boostActive = false;
let superBoostCooldown = parseInt(localStorage.getItem('superBoostCooldown')) || 0;

const levels = [
    { name: "Bronze", minTaps: 0 },
    { name: "Silver", minTaps: 10000000 },
    { name: "Gold", minTaps: 50000000 },
    { name: "Platinum", minTaps: 200000000 },
    { name: "Veteran", minTaps: 1000000000 },
    { name: "Master", minTaps: 10000000000 },
    { name: "Legend", minTaps: 50000000000 }
];

function formatNumber(num) {
    return num.toLocaleString();
}

function getLevel(taps) {
    return levels.reduce((prev, curr) => (taps >= curr.minTaps ? curr : prev), levels[0]);
}

function updateCounter() {
    counterElement.textContent = formatNumber(count);
    updateLevel();
}

function updateWallet() {
    walletElement.textContent = `$${formatNumber(balance)}`;
}

function updateProgressBar() {
    const progress = (taps % 1000) / 1000;
    progressBar.style.width = `${progress * 100}%`;
    progressText.textContent = `${1000 - (taps % 1000)}/1000`;
}

function updateLevel() {
    const level = getLevel(taps);
    levelElement.textContent = level.name;
}

function createFlyOut() {
    const flyOut = document.createElement('div');
    flyOut.classList.add('fly-out');
    flyOut.textContent = `+${tapValue}`;
    document.body.appendChild(flyOut);
    flyOut.style.left = `${dollarImage.getBoundingClientRect().left}px`;
    flyOut.style.top = `${dollarImage.getBoundingClientRect().top}px`;
    setTimeout(() => document.body.removeChild(flyOut), 1000);
}

dollarImage.addEventListener('click', () => {
    count += tapValue;
    balance += tapValue;
    taps += tapValue;

    if (boostActive) {
        tapValue = 1;
        boostActive = false;
    }

    localStorage.setItem('count', count);
    localStorage.setItem('balance', balance);
    localStorage.setItem('taps', taps);

    createFlyOut();
    updateCounter();
    updateWallet();
    updateProgressBar();
});

sendButton.addEventListener('click', () => {
    withdrawFormElem.submit();
});

document.getElementById('referralButton').addEventListener('click', () => {
    const shareText = "Join Me To Tap And Earn Dollars";
    const shareUrl = "https://play.google.com/store/apps/details?id=com.techex.palmtap";
    if (navigator.share) {
        navigator.share({
            title: 'Tap to Earn',
            text: shareText,
            url: shareUrl
        });
    } else {
        const textArea = document.createElement("textarea");
        textArea.value = `${shareText}\n${shareUrl}`;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert('Referral link copied to clipboard!');
    }
});

document.getElementById('homeButton').addEventListener('click', () => {
    window.location.href = '/palmtap/index.html';
});

document.getElementById('superBoostButton').addEventListener('click', () => {
    if (superBoostCooldown <= Date.now()) {
        if (!boostActive) {
            boostActive = true;
            tapValue = 20000;
            superBoostCooldown = Date.now() + 24 * 3600000; // 24 hours
            localStorage.setItem('superBoostCooldown', superBoostCooldown);
            setTimeout(() => {
                document.getElementById('superBoostButton').disabled = false;
            }, 24 * 3600000); // 24 hours
            document.getElementById('superBoostButton').disabled = true;
        }
    }
});

function checkSuperBoost() {
    const cooldownTimeLeft = superBoostCooldown - Date.now();
    if (cooldownTimeLeft > 0) {
        document.getElementById('superBoostButton').disabled = true;
        setTimeout(() => {
            document.getElementById('superBoostButton').disabled = false;
        }, cooldownTimeLeft);
    } else {
        document.getElementById('superBoostButton').disabled = false;
    }
}

checkSuperBoost();
updateCounter();
updateWallet();
updateProgressBar();
updateLevel();
