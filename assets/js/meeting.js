window.onload = async () => {
    await getRecordings();
    document.getElementById("recordings-container").classList.add("animated");
    await getPamphlets();
    document.getElementById("pamphlets-container").classList.add("animated");
}

const getRecordings = async () => {
    const recordingsContainer = document.getElementById("recordings-container");
    const recordingsPlaceholder = document.getElementById("recordings-placeholder");

    let recordings = await fetch(recordingsObject.ajaxUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            action: "mtu_recordings",
            post_id: recordingsObject.postId,
            _ajax_nonce: recordingsObject.nonce
        })
    }).then(res => res.json());
    
    if(!recordings.success) return recordingsPlaceholder.innerText = "امکان نمایش جلسات ضبط شده وجود ندارد.";
    if(recordings.data.length == 0) return recordingsPlaceholder.innerText = "جلسه ضبط شده‌ای برای نمایش وجود ندارد";

    recordingsPlaceholder.innerText = "";
    recordings.data.forEach(recording => {
        recordingsContainer.innerHTML += `
        <div class="item">
                <div class="item-mark"></div>
                <div class="item-main-data">
                    <h3 class="meeting-title">${recording.title}</h3>
                    <p class="meeting-category"><?= the_title(); ?></p>
                </div>
                <div class="item-meta meeting-status">
                    <p class="meta-value">${recording.date}</p>
                    <p class="meta-name">زمان برگزاری</p>
                </div>
                <a href="${recording.url[0]}" class="mtu-btn">مشاهده آفلاین</a>
            </div>
            `;
    });
}

const getPamphlets = async () => {
    const pamphletsContainer = document.getElementById("pamphlets-container");
    const pamphletsPlaceholder = document.getElementById("pamphlets-placeholder");

    let pamphlets = await fetch(pamphletsObject.ajaxUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            action: "mtu_pamphlets",
            post_id: pamphletsObject.postId,
            _ajax_nonce: pamphletsObject.nonce
        })
    }).then(res => res.json());

    if(!pamphlets.success) return pamphletsPlaceholder.innerText = "امکان نمایش جزوات کلاس وجود ندارد.";
    if(pamphlets.data.length == 0) return pamphletsPlaceholder.innerText = "جزوه‌ای برای نمایش وجود ندارد.";

    pamphletsPlaceholder.remove();
    pamphlets.data.forEach(pamphlet => {
        pamphletsContainer.innerHTML += `
        <a href="${pamphlet.url}" class="pamphlet">
            <div class="pamphlet-icon"><span class="dashicons dashicons-media-default"></span></div>
            <h4 class="pamphlet-name">${pamphlet.title}</h4>
        </a>
        `
    })
}