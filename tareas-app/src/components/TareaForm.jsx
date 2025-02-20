import React, { useState, useEffect } from 'react';
import axios from 'axios';

const TareaForm = ({ tareaEditar, onTareaGuardada }) => {
    const [tarea, setTarea] = useState({
        titulo: '',
        descripcion: '',
        estado: 'pendiente'
    });

    useEffect(() => {
        if (tareaEditar) {
            setTarea(tareaEditar);
        } else {
            setTarea({
                titulo: '',
                descripcion: '',
                estado: 'pendiente'
            });
        }
    }, [tareaEditar]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        try {
            const API_URL = 'http://localhost:8000/ws/api/tareas.php';
            
            if (tareaEditar) {
                await axios.put(API_URL, { ...tarea, id: tareaEditar.id });
            } else {
                await axios.post(API_URL, tarea);
            }
            
            onTareaGuardada();
            setTarea({
                titulo: '',
                descripcion: '',
                estado: 'pendiente'
            });
        } catch (error) {
            console.error('Error al guardar tarea:', error);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setTarea(prevTarea => ({
            ...prevTarea,
            [name]: value
        }));
    };

    return (
        <div className="card">
            <div className="card-body">
                <h3>{tareaEditar ? 'Editar Tarea' : 'Nueva Tarea'}</h3>
                <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                        <label className="form-label">Título</label>
                        <input
                            type="text"
                            className="form-control"
                            name="titulo"
                            value={tarea.titulo}
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <div className="mb-3">
                        <label className="form-label">Descripción</label>
                        <textarea
                            className="form-control"
                            name="descripcion"
                            value={tarea.descripcion}
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <div className="mb-3">
                        <label className="form-label">Estado</label>
                        <select
                            className="form-select"
                            name="estado"
                            value={tarea.estado}
                            onChange={handleChange}
                        >
                            <option value="pendiente">Pendiente</option>
                            <option value="en_progreso">En Progreso</option>
                            <option value="completada">Completada</option>
                        </select>
                    </div>
                    <button type="submit" className="btn btn-primary">
                        {tareaEditar ? 'Actualizar' : 'Crear'}
                    </button>
                </form>
            </div>
        </div>
    );
};

export default TareaForm; 