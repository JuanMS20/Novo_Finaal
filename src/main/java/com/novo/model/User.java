@Entity
@Data
@NoArgsConstructor
@AllArgsConstructor
public class User {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    private String fullName;
    
    @Column(unique = true)
    private String email;
    
    private String password;
    
    private String role;
    
    @CreationTimestamp
    private LocalDateTime createdAt;
} 