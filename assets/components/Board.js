import React, {useState} from "react";
import Square from "./Square";
import axios from "axios";
import Swal from "sweetalert2";

const Board = (props) => {
    const [squares, setSquares] = useState([]);
    const [status, setStatus] = useState("Turno del jugador X");

    const handleClick = (cellIndex) => {
        axios.patch('/api/' + props.gameIndex, {cell: cellIndex})
            .then(function (response) {

                setStatus("Turno del jugador " + response.data.next);

                if (response.data.status && response.data.status === 404) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error, al parecer el juego no se pudo crear correctamente. Inicie uno nuevo!',
                        showConfirmButton: true
                    })
                    return;
                }

                if (response.data.status && response.data.status === 400) {
                    const title = response.data.winner ? 'Error, ya ganó el jugador ' + response.data.winner : 'Error, el juego ya terminó en empate';
                    Swal.fire({
                        icon: 'error',
                        title: title,
                        showConfirmButton: true
                    })
                    return;
                }

                if (response.data.winner !== '-' && response.data.canBePlayed === false) {
                    return;
                }

                if (response.data.winner !== '-' && response.data.canBePlayed === true) {

                    switch (response.data.winner) {
                        case 'X':
                            setStatus('Ganó el jugador X');
                            props.incrementX();
                            Swal.fire({
                                icon: 'success',
                                title: status,
                                showConfirmButton: true
                            })
                            break;
                        case 'O':
                            setStatus('Ganó el jugador O');
                            props.incrementO();
                            Swal.fire({
                                icon: 'success',
                                title: status,
                                showConfirmButton: true
                            })
                            break;
                        default:
                            setStatus('El juego terminó en empate');
                            props.incrementD();
                            Swal.fire({
                                icon: 'success',
                                title: status,
                                showConfirmButton: true
                            })
                            break;
                    }
                }

                setSquares(response.data.cells);
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar el estado del juego!',
                    showConfirmButton: true
                })
            });
    }

    const renderSquare = (cellIndex) => {
        return (
            <Square
                value={squares[cellIndex]}
                onClick={() => handleClick(cellIndex)}
            />
        );
    }

    return (
        <div className="container-column">
            <h1>Juego entre 2 jugadores</h1>
            <div className="play-area">
                <div id="block_0" className="block">{renderSquare(0)}</div>
                <div id="block_1" className="block">{renderSquare(1)}</div>
                <div id="block_2" className="block">{renderSquare(2)}</div>
                <div id="block_3" className="block">{renderSquare(3)}</div>
                <div id="block_4" className="block">{renderSquare(4)}</div>
                <div id="block_5" className="block">{renderSquare(5)}</div>
                <div id="block_6" className="block">{renderSquare(6)}</div>
                <div id="block_7" className="block">{renderSquare(7)}</div>
                <div id="block_8" className="block">{renderSquare(8)}</div>
            </div>
            <div className="card mt-15 mb-15">
                <div className="container-card">
                    <h4>Tabla resumen de los juegos históricos:</h4>
                    <p>Juegos ganados por el jugador "X": {props.xWinned}</p>
                    <p>Juegos ganados por el "O": {props.oWinned}</p>
                    <p>Juegos empatados: {props.draws}</p>
                </div>
            </div>
            <h3 className="status mt-15">{status}</h3>
        </div>
    );

}

export default Board;
