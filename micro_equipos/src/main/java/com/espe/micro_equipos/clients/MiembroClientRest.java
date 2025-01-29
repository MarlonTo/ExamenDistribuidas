package com.espe.micro_equipos.clients;

import com.espe.micro_equipos.models.Miembro;
import org.springframework.cloud.openfeign.FeignClient;
import org.springframework.web.bind.annotation.*;

@FeignClient(name = "micro-miembros", url = "http://localhost:8002/api/miembros")
public interface MiembroClientRest {
    @GetMapping("/{id}")
    Miembro findById(@PathVariable Long id);
}
