import React from "react";


const Buttons = (props) => {

    return (
        <div className="container-row">
            <button className="button mr-15" onClick={props.onClickTwoPlayers}>Nuevo Juego(2 jugadores)</button>
            <button className="button" onClick={props.onClickVsComputer}>Nuevo Juego(vs computadora)</button>
        </div>
    );

}

export default Buttons;