package com.espe.micro_miembros.controller;

import com.espe.micro_miembros.models.entities.Miembro;
import com.espe.micro_miembros.services.MiembroService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/miembros")
public class MiembroController {

    @Autowired
    private MiembroService service;

    @PostMapping
    public ResponseEntity<?> create(@Valid @RequestBody Miembro miembro, BindingResult result) {
        if (result.hasErrors()) {
            return validar(result);
        }
        try {
            return ResponseEntity.status(HttpStatus.CREATED).body(service.save(miembro));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                    .body(Map.of("mensaje", "Error al crear el miembro: " + e.getMessage()));
        }
    }

    @PutMapping("/{id}")
    public ResponseEntity<?> update(@PathVariable Long id, @Valid @RequestBody Miembro miembro, BindingResult result) {
        if (result.hasErrors()) {
            return validar(result);
        }
        try {
            miembro.setIdMiembro(id);
            return ResponseEntity.status(HttpStatus.OK).body(service.save(miembro));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                    .body(Map.of("mensaje", "Error al actualizar el miembro: " + e.getMessage()));
        }
    }

    @GetMapping
    public ResponseEntity<?> findAll() {
        try {
            return ResponseEntity.ok().body(service.findAll());
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al obtener los miembros: " + e.getMessage()));
        }
    }

    @GetMapping("/{id}")
    public ResponseEntity<?> findById(@PathVariable Long id) {
        try {
            return ResponseEntity.ok().body(service.findById(id)
                    .orElseThrow(() -> new RuntimeException("Miembro no encontrado con ID: " + id)));
        } catch (RuntimeException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND)
                    .body(Map.of("mensaje", e.getMessage()));
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al obtener el miembro: " + e.getMessage()));
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<?> delete(@PathVariable Long id) {
        try {
            service.deleteById(id);
            return ResponseEntity.noContent().build();
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("mensaje", "Error al eliminar el miembro: " + e.getMessage()));
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
