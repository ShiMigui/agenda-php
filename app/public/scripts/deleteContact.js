async function deleteContact(e) {
    const $this = e.currentTarget;
    const HREF = $this.getAttribute("data-href");

    try {
        if (!HREF) throw new Error("data-href is not defined!");

        const FETCH = await fetch(HREF);
        if (!FETCH.ok) throw new Error(`HTTP error! Status: ${FETCH.status}`);

        const JSON = await FETCH.json();

        if (!JSON.ok) {
            console.error("Failed to delete contact:", JSON.msg);
            return;
        }

        $this.parentNode.parentNode.remove();
    } catch (error) {
        console.error("Error during deletion:", error);
    }
}