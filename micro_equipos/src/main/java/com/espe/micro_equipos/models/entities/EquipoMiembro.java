package com.espe.micro_equipos.models.entities;


import jakarta.persistence.*;

@Entity
@Table(name = "equipos_miembros")
public class EquipoMiembro {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    @Column(name = "miembro_id")
    private Long miembroId;

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Long getMiembroId() {
        return miembroId;
    }

    public void setMiembroId(Long miembroId) {
        this.miembroId = miembroId;
    }
}
