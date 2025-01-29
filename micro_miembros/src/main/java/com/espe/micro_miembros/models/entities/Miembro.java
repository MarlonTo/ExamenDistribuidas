package com.espe.micro_miembros.models.entities;

import jakarta.persistence.*;
import jakarta.validation.constraints.*;
import java.util.Date;

@Entity
@Table(name = "miembros")
public class Miembro {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "idMiembro")
    private Long idMiembro;

    @NotEmpty(message = "El nombre del miembro no puede estar vacío")
    @Size(min = 3, max = 100, message = "El nombre debe tener entre 3 y 100 caracteres")
    @Column(name = "nombreMiembro", nullable = false, length = 100)
    private String nombreMiembro;

    @NotEmpty(message = "El correo no puede estar vacío")
    @Email(message = "El formato del correo no es válido")
    @Column(name = "correoMiembro", nullable = false, unique = true, length = 100)
    private String correoMiembro;

    @Pattern(regexp = "\\d{10}", message = "El teléfono debe tener 10 dígitos")
    @Column(name = "telefono", length = 15)
    private String telefono;

    @Column(name = "fechaRegistro", updatable = false)
    @Temporal(TemporalType.TIMESTAMP)
    private Date fechaRegistro;

    @PrePersist
    public void prePersist() {
        this.fechaRegistro = new Date();
    }

    // Getters y Setters

    public Long getIdMiembro() {
        return idMiembro;
    }

    public void setIdMiembro(Long idMiembro) {
        this.idMiembro = idMiembro;
    }

    public String getNombreMiembro() {
        return nombreMiembro;
    }

    public void setNombreMiembro(String nombreMiembro) {
        this.nombreMiembro = nombreMiembro;
    }

    public String getCorreoMiembro() {
        return correoMiembro;
    }

    public void setCorreoMiembro(String correoMiembro) {
        this.correoMiembro = correoMiembro;
    }

    public String getTelefono() {
        return telefono;
    }

    public void setTelefono(String telefono) {
        this.telefono = telefono;
    }

    public Date getFechaRegistro() {
        return fechaRegistro;
    }

    public void setFechaRegistro(Date fechaRegistro) {
        this.fechaRegistro = fechaRegistro;
    }
}
