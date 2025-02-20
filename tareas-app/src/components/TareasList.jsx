import React, { useState, useEffect } from 'react';
import axios from 'axios';
import TareaForm from './TareaForm';

const TareasList = () => {
    const [tareas, setTareas] = useState([]);
    const [tareaEditar, setTareaEditar] = useState(null);

    const API_URL = 'http://localhost:8000/ws/api/tareas.php';

    useEffect(() => {
        cargarTareas();
    }, []);

    const cargarTareas = async () => {
        try {
            const response = await axios.get(API_URL);
            setTareas(response.data);
        } catch (error) {
            console.error('Error al cargar tareas:', error);
        }
    };

    const eliminarTarea = async (id) => {
        if (window.confirm('¿Estás seguro de eliminar esta tarea?')) {
            try {
                await axios.delete(API_URL, { data: { id } });
                cargarTareas();
            } catch (error) {
                console.error('Error al eliminar tarea:', error);
            }
        }
    };

    const editarTarea = (tarea) => {
        setTareaEditar(tarea);
    };

    return (
        <div className="container mt-4">
            <h2 className="mb-4">Gestión de Tareas</h2>
            
            <TareaForm 
                tareaEditar={tareaEditar}
                onTareaGuardada={() => {
                    cargarTareas();
                    setTareaEditar(null);
                }}
            />

            <div className="mt-4">
                <h3>Lista de Tareas</h3>
                <table className="table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {tareas.map((tarea) => (
                            <tr key={tarea.id}>
                                <td>{tarea.titulo}</td>
                                <td>{tarea.descripcion}</td>
                                <td>
                                    <span className={`badge ${
                                        tarea.estado === 'completada' ? 'bg-success' :
                                        tarea.estado === 'en_progreso' ? 'bg-warning' :
                                        'bg-secondary'
                                    }`}>
                                        {tarea.estado}
                                    </span>
                                </td>
                                <td>
                                    <button 
                                        className="btn btn-sm btn-primary me-2"
                                        onClick={() => editarTarea(tarea)}
                                    >
                                        Editar
                                    </button>
                                    <button 
                                        className="btn btn-sm btn-danger"
                                        onClick={() => eliminarTarea(tarea.id)}
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default TareasList; 