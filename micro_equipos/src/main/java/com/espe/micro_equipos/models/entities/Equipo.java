package com.espe.micro_equipos.models.entities;

import com.espe.micro_equipos.models.Miembro;
import jakarta.persistence.*;
import jakarta.validation.constraints.*;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

@Entity
@Table(name = "equipos")
public class Equipo {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotEmpty(message = "El nombre del equipo no puede estar vacío")
    @Size(min = 3, max = 100, message = "El nombre debe tener entre 3 y 100 caracteres")
    @Column(name = "nombreEquipo", nullable = false, length = 100)
    private String nombre;

    @Size(max = 500, message = "La descripción no puede exceder los 500 caracteres")
    @Column(name = "descripcion", columnDefinition = "TEXT")
    private String descripcion;

    @Column(name = "fechaCreacion", updatable = false)
    @Temporal(TemporalType.TIMESTAMP)
    private Date fechaCreacion;

    @PrePersist
    public void prePersist() {
        this.fechaCreacion = new Date();
    }

    @OneToMany(cascade = CascadeType.ALL, orphanRemoval = true)
    @JoinColumn(name = "equipo_id")
    private List<EquipoMiembro> equipoMiembros;

    @Transient
    private List<Miembro> miembros;

    public Equipo() {
        equipoMiembros = new ArrayList<>();
        miembros = new ArrayList<>();
    }

    public void addEquipoMiembro(EquipoMiembro equipoMiembro) {
        this.equipoMiembros.add(equipoMiembro);
    }

    public void removeEquipoMiembro(EquipoMiembro equipoMiembro) {
        this.equipoMiembros.remove(equipoMiembro);
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public Date getFechaCreacion() {
        return fechaCreacion;
    }

    public void setFechaCreacion(Date fechaCreacion) {
        this.fechaCreacion = fechaCreacion;
    }

    public List<EquipoMiembro> getEquipoMiembros() {
        return equipoMiembros;
    }

    public void setEquipoMiembros(List<EquipoMiembro> equipoMiembros) {
        this.equipoMiembros = equipoMiembros;
    }

    public List<Miembro> getMiembros() {
        return miembros;
    }

    public void setMiembros(List<Miembro> miembros) {
        this.miembros = miembros;
    }
}
