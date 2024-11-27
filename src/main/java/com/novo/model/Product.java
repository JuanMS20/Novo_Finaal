@Entity
@Data
@NoArgsConstructor
@AllArgsConstructor
public class Product {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    private String title;
    private String description;
    private Double price;
    private String category;
    private String condition;
    private String imageUrl;
    
    @ManyToOne
    private User seller;
    
    @CreationTimestamp
    private LocalDateTime createdAt;
} 