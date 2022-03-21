import React, {useState} from "react";
import Square from "./Square";
import axios from "axios";
import Swal from "sweetalert2";

const Board = (props) => {
    const [squares, setSquares] = useState([]);
    const [status, setStatus] = useState("Turno del jugador X");

    const handleClick = (cellIndex) => {
        axios.patch('/api/' + props.gameIndex, {cell: cellIndex, playVs: props.playVs})
            .then(function (response) {

                updateGameStatusIfNextTurn(response);

                notifyIfGameWasNotCreatedSuccessfully(response);

                notifyIfGameIsAlreadyFinished(response);

                notifyIfCanNotBePlayedMove(response);

                notifyWhoWonTheGame(response);

                updateCellsIfPossible(response);
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar el estado del juego!',
                    showConfirmButton: true
                })
            });
    }

    const updateGameStatusIfNextTurn = (response) => {
        if (response.data.next) {
            setStatus("Turno del jugador " + response.data.next);
        }
    }

    const notifyIfGameWasNotCreatedSuccessfully = (response) => {
        if (response.data.status === 404) {
            Swal.fire({
                icon: 'error',
                title: 'Error, al parecer el juego no se pudo crear correctamente. Inicie uno nuevo!',
                showConfirmButton: true
            })
        }
    }

    const notifyIfGameIsAlreadyFinished = (response) => {
        if (response.data.status === 400) {
            const title = response.data.winner ? 'Error, ya ganó el jugador ' + response.data.winner : 'Error, el juego ya terminó en empate';
            Swal.fire({
                icon: 'error',
                title: title,
                showConfirmButton: true
            })
        }
    }

    const canBePlayedMove = (response) => {
        return response.data.canBePlayed === true;
    }

    const canNotBePlayedMove = (response) => {
        return response.data.canBePlayed === false;
    }

    const notifyIfCanNotBePlayedMove = (response) => {
        if (canNotBePlayedMove(response)) {
            Swal.fire({
                icon: 'error',
                title: 'Esta casillada ya ha sido jugada',
                showConfirmButton: true
            })
        }
    }

    const isThereAlreadyAWinner = (response) => {
        return response.data.winner !== '-';
    }

    function notifyWhoWonTheGame(response) {
        if (isThereAlreadyAWinner(response) && canBePlayedMove(response)) {

            switch (response.data.winner) {
                case 'X':
                    setStatus('Ganó el jugador X');
                    props.incrementX();
                    Swal.fire({
                        icon: 'success',
                        title: 'Ganó el jugador X',
                        showConfirmButton: true
                    })
                    break;
                case 'O':
                    setStatus('Ganó el jugador O');
                    props.incrementO();
                    Swal.fire({
                        icon: 'success',
                        title: 'Ganó el jugador O',
                        showConfirmButton: true
                    })
                    break;
                default:
                    setStatus('El juego terminó en empate');
                    props.incrementD();
                    Swal.fire({
                        icon: 'success',
                        title: 'El juego terminó en empate',
                        showConfirmButton: true
                    })
                    break;
            }
        }
    }

    const updateCellsIfPossible = (response) => {
        if (response.data.cells) {
            setSquares(response.data.cells);
        }
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
            <h1>{props.playVs === "player" ? "Juego entre jugadores alternos" : "Juego vs Computadora"}</h1>
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
                    <p>Juegos ganados por el jugador "X": {props.xWon}</p>
                    <p>Juegos ganados por el "O": {props.oWon}</p>
                    <p>Juegos empatados: {props.draws}</p>
                </div>
            </div>
            <h3 className="mt-15">{status}</h3>
        </div>
    );

}

export default Board;
