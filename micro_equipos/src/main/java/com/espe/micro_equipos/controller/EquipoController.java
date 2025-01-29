package com.espe.micro_equipos.controller;

import com.espe.micro_equipos.models.Miembro;
import com.espe.micro_equipos.models.entities.Equipo;
import com.espe.micro_equipos.models.entities.EquipoMiembro;
import com.espe.micro_equipos.services.EquipoService;
import feign.FeignException;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import java.util.Collections;
import java.util.HashMap;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/equipos")
public class EquipoController {

    @Autowired
    private EquipoService service;

    @PostMapping
    public ResponseEntity<?> create(@Valid @RequestBody Equipo equipo, BindingResult result) {
        if (result.hasErrors()) {
            return validar(result);
        }
        try {
            return ResponseEntity.status(HttpStatus.CREATED).body(service.save(equipo));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                    .body(Map.of("mensaje", "Error al crear el equipo: " + e.getMessage()));
        }
    }

    @PutMapping("/{id}")
    public ResponseEntity<?> update(@PathVariable Long id, @Valid @RequestBody Equipo equipo, BindingResult result) {
        if (result.hasErrors()) {
            return validar(result);
        }
        try {
            equipo.setId(id);
            return ResponseEntity.status(HttpStatus.OK).body(service.save(equipo));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                    .body(Map.of("mensaje", "Error al actualizar el equipo: " + e.getMessage()));
        }
    }

    @GetMapping
    public ResponseEntity<?> findAll() {
        try {
            return ResponseEntity.ok().body(service.findAll());
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al obtener los equipos: " + e.getMessage()));
        }
    }

    @GetMapping("/{id}")
    public ResponseEntity<?> findById(@PathVariable Long id) {
        try {
            return ResponseEntity.ok().body(service.findById(id)
                    .orElseThrow(() -> new RuntimeException("Equipo no encontrado con ID: " + id)));
        } catch (RuntimeException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND)
                    .body(Map.of("mensaje", e.getMessage()));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al obtener el equipo: " + e.getMessage()));
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<?> delete(@PathVariable Long id) {
        try {
            service.deleteById(id);
            return ResponseEntity.noContent().build();
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al eliminar el equipo: " + e.getMessage()));
        }
    }

    @PostMapping("/{id}/miembros")
    public ResponseEntity<?> agregarMiembro(@Valid @RequestBody Miembro miembro, @PathVariable Long id) {
        try {
            Optional<Miembro> optional = service.addMember(miembro, id);
            if (optional.isPresent()) {
                return ResponseEntity.status(HttpStatus.CREATED).body(optional.get());
            } else {
                return ResponseEntity.status(HttpStatus.NOT_FOUND)
                    .body(Map.of("mensaje", "No se encontró el equipo con ID: " + id));
            }
        } catch (FeignException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND)
                .body(Map.of("mensaje", "Miembro no encontrado: " + e.getMessage()));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                .body(Map.of("mensaje", "Error al agregar el miembro: " + e.getMessage()));
        }
    }

    @DeleteMapping("/{equipoId}/miembros/{miembroId}")
    public ResponseEntity<?> eliminarMiembro(@PathVariable Long equipoId, @PathVariable Long miembroId) {
        try {
            service.removeMember(equipoId, miembroId);
            return ResponseEntity.noContent().build();
        } catch (RuntimeException e) {
            // Para errores específicos como "miembro no encontrado" o "último miembro"
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                    .body(Map.of("mensaje", e.getMessage()));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al eliminar el miembro del equipo: " + e.getMessage()));
        }
    }

    private ResponseEntity<?> validar(BindingResult result) {
        Map<String, String> errores = new HashMap<>();
        result.getFieldErrors().forEach(err -> 
            errores.put(err.getField(), err.getDefaultMessage())
        );
        return ResponseEntity.badRequest().body(errores);
    }
}
