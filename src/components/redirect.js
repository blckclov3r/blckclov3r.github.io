import Opener from "./opener";
export const KEY = {
    URL : "https://blckclov3r.netlify.app",
    SELF : "_self",
    PROGRAMMER: "Aljun Abrenica"
}

const Redirect = () => {
    return (
        Opener(KEY.URL,KEY.SELF)
    );
}

export default Redirect;
