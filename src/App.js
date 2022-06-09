import { useEffect } from "react";
import { DEVELOPER } from "./components/developer";
import Redirect from "./components/redirect";

function App() {

  useEffect(() => {
     if(DEVELOPER === "Aljun Abrenica"){
        Redirect();
     }
   }, []);

  return (
    <div className="App">
      <h1 style={{color: "#fff"}}>Blckclov3r</h1>
      <h2 style={{color: "#fff"}}>Blckclov3r</h2>
      <h3 style={{color: "#fff"}}>Blckclov3r</h3>
      <h4 style={{color: "#fff"}}>Blckclov3r</h4>
      <h5 style={{color: "#fff"}}>Blckclov3r</h5>
      <h6 style={{color: "#fff"}}>Blckclov3r</h6>
    </div>
  );
}

export default App;
